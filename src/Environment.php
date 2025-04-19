<?php
declare(strict_types=1);

namespace HonkLegion;

use function array_filter;
use function array_map;
use function explode;
use function rtrim;
use function strtolower;
use function strtoupper;

class Environment
{
	public const String = 'string';
	public const Bool = 'bool';
	public const Int = 'int';
	public const Float = 'float';
	protected const Delimiter = '|';
	protected static string $prefix = '';

	/** @deprecated use Environment::String instead */
	public const STRING = self::String;
	/** @deprecated use Environment::String instead */
	public const INT = self::Int;
	/** @deprecated use Environment::Int instead */
	public const INTEGER = self::Int;
	/** @deprecated use Environment::Int instead */
	public const FLOAT = self::Float;
	/** @deprecated use Environment::Float instead */
	public const BOOL = self::Bool;
	/** @deprecated use Environment::Bool instead */
	public const BOOLEAN = self::Bool;

	public function __construct(
		private string $name,
		private string $default = ''
	) {
	}

	/**
	 * @api
	 */
	public static function setPrefix(string $prefix = ''): void
	{
		self::$prefix = $prefix ? strtoupper(rtrim($prefix, '_')) . '_' : '';
	}

	/**
	 * @api
	 */
	public static function bool(string $name, bool $default = false): bool
	{
		return (bool)(self::castIt(self::env($name), self::Bool) ?? $default);
	}

	private static function castIt(?string $value, string $cast): null|string|bool|int|float
	{
		return ($value !== null) ? match ($cast) {
			self::Bool => !($value === 'false' || !$value),
			self::Int => (int)$value,
			self::Float => (float)$value,
			default => $value,
		} : null;
	}

	private static function env(string $name): ?string
	{
		$env = getenv(self::$prefix . $name);
		return (false === $env) ? null : $env;
	}

	/**
	 * @api
	 */
	public static function int(string $name, int $default = 0): int
	{
		return (int)(self::env($name) ?? $default);
	}

	/**
	 * @api
	 */
	public static function float(string $name, float $default = 0): float
	{
		return (float)(self::castIt(self::env($name), self::Float) ?? $default);
	}

	/**
	 * @api
	 */
	public function __toString(): string
	{
		return self::env($this->name) ?? $this->default;
	}

	/**
	 * @api
	 */
	public function get(string $cast = self::String): string|bool|int|float
	{
		return self::castIt(self::env($this->name) ?? $this->default, strtolower($cast)) ?? '';
	}

	/**
	 * @api
	 * @param string $cast
	 * @return bool[]|float[]|int[]|string[]
	 */
	public function toArray(string $cast = self::String): array
	{
		return self::array($this->name, $this->default ?: self::Delimiter, $cast);
	}

	/**
	 * @api
	 * @param string $name
	 * @param non-empty-string $separator
	 * @param string $cast
	 * @return string[]|int[]|float[]|bool[]
	 */
	public static function array(string $name, string $separator = self::Delimiter, string $cast = self::String): array
	{
		return
			array_filter(
				array_map(
					static fn($item) => self::castIt($item, $cast),
					array_filter(
						explode($separator, self::string($name)),
						static fn($item) => $item !== ''
					)
				),
				static fn($item) => $item !== null
			);
	}


	/**
	 * @api
	 */
	public static function string(string $name, string $default = ''): string
	{
		return self::env($name) ?? $default;
	}
}
