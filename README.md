# HonkLegion/Environment
Easy class for getting ENV values with casting and default value fallback. Great for frameworks like Nette.

[![Coverage Status](https://img.shields.io/coveralls/github/bckp/environment/master)](https://coveralls.io/github/bckp/environment?branch=master)
[![Tests](https://github.com/bckp/environment/actions/workflows/tests.yml/badge.svg)](https://github.com/bckp/environment/actions/workflows/tests.yml)
[![Downloads this Month](https://img.shields.io/packagist/dm/bckp/environment.svg)](https://packagist.org/packages/bckp/environment)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/quality/g/bckp/environment/master)](https://scrutinizer-ci.com/g/bckp/environment/?branch=master)
[![Latest stable](https://img.shields.io/packagist/v/bckp/environment.svg)](https://packagist.org/packages/bckp/environment)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/bckp/environment/blob/master/license.md)

Installation
------------

The best way to install `honklegion/environment` is using [Composer](http://getcomposer.org/):

```sh
$ composer require honklegion/environment
```

## Example

imagine, you have set your ENV as follows:

```env
FOO=false
BAR=1
KEYS=1|2|3
```

Lets see PHP code

```php
use HonkLegion\Environment;

// Using getenv function:
var_dump(getenv('FOO')); // string(false)

// Using Environment function:
var_dump(Environment::Bool('foo')); //bool(false)

// But we can go even further:
var_dump(Environment::Bool('BAR')); //bool(true)
var_dump(Environment::Float('BAR')); //float(1.0)

// What about multi-values?
var_dump(Environment::array('KEYS', '|', Environment::String)); //array(3) [0 => '1', 1 => '2', 2 => '3'] - notice we have strings
var_dump(Environment::array('KEYS', '|', Environment::Int)); //array(3) [0 => 1, 1 => 2, 2 => 3] - notice we have int

// Default values? No problem!
var_dump(Environment::Bool('prod', true)); //bool(true) * even bin is not set
```

## Development

This package is currently maintaining by these authors.

<a href="https://github.com/HonkLegion"><img width="80" height="80" src="https://avatars.githubusercontent.com/u/208159255?s=80&v=4"></a>
