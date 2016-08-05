# Commander

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gravitymedia/commander.svg)](https://packagist.org/packages/gravitymedia/commander)
[![Software License](https://img.shields.io/packagist/l/gravitymedia/commander.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/GravityMedia/Commander.svg)](https://travis-ci.org/GravityMedia/Commander)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/GravityMedia/Commander.svg)](https://scrutinizer-ci.com/g/GravityMedia/Commander/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/GravityMedia/Commander.svg)](https://scrutinizer-ci.com/g/GravityMedia/Commander)
[![Total Downloads](https://img.shields.io/packagist/dt/gravitymedia/commander.svg)](https://packagist.org/packages/gravitymedia/commander)
[![Dependency Status](https://img.shields.io/versioneye/d/php/gravitymedia:commander.svg)](https://www.versioneye.com/user/projects/57605b3d49310500437fb418)

Commander is a task manager/runner application for PHP.

## Requirements

This application has the following requirements:

- PHP 5.6+
- ext-sqlite

## Usage

Commander was designed to be used as a type of SQLite based task manager. You can add new tasks to the schedule, run
them and watch the result. A task consists of a commandline which will be executed and an optional priority. For now
the tasks are unique and can only be added once to the schedule.

You can use Commander in one of the three following ways.

### As a Phar

This is the recommended way of using Commander. Download the newest Phar from the [releases](../../contributors).
Commander can then be executed out of the box by running:

``` bash
$ php commander.phar
```

### As a global Composer package

Install composer in your project:

``` bash
$ curl -s https://getcomposer.org/installer | php
```

Require the package as a global dependency via Composer:

``` bash
$ php composer.phar global require gravitymedia/commander
```

### As a Composer dependency

Install composer in your project:

``` bash
$ curl -s https://getcomposer.org/installer | php
```

Require the package as a dependency for development via Composer:

``` bash
$ php composer.phar require --dev gravitymedia/commander
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Daniel Schröder](https://github.com/pCoLaSD)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
