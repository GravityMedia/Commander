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
- ext-pdo_sqlite

## Usage

Commander was designed to be used as a type of SQLite based task manager and runner. You can add new tasks to the
schedule, run them and watch the result. A task consists of a commandline which will be executed and an optional
priority. For now the tasks are unique and can only be added once to the schedule.

You can use Commander in one of the three following ways.

### As a Phar

This is the recommended way of using Commander. Download the latest Phar from the [releases](../../releases) section.

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

## Configuration

Commander can be configured with a JSON file named `commander.json`. This file should be located in the current working
directory. It's location can also be defined with the `--configuration` option.

| Name             | Description                                                            |
|------------------|------------------------------------------------------------------------|
| databaseFilePath | The file path to the database file                                     |
| cacheDirectory   | The directory where the cache files will be stored (e.g. `.commander`) |
| logFilePath      | The file path to the log file (e.g. `commander.log`)                   |
| processTimeout   | The timeout after which every task will be killed                      | 

The following configuration complies with the default configuration:
 
``` json
{
    "databaseFilePath": "commander.sqlite",
    "cacheDirectory": null,
    "logFilePath": null,
    "processTimeout": 60
}
```

## Examples

You can list all the commands by running Commander without an argument.

For the following examples assume that Commander is available as a Phar in the current working directory.

### Create new task or update existing task

``` bash
$ php commander.phar join -- 'printf "Hello world!"'
$ php commander.phar join --priority=1000 -- 'printf "Hello world!"'
$ php commander.phar join -- 'printf "Let ms say hello:"'
```

The first command will join a task that will later print out `Hello world!`. The next command will change the priority
of this task, so the next task will then run (with default priority) before this one.

### Show information about all joined tasks

This command will list all tasks:

``` bash
$ php commander.phar show
```

The output will be rendered as an ASCII table.
```
+--------------------------------------+----------+----------------------------+-----+-----------+---------------------+---------------------+
| ID                                   | Priority | Commandline                | PID | Exit Code | Created At          | Updated At          |
+--------------------------------------+----------+----------------------------+-----+-----------+---------------------+---------------------+
| 2E906453-41D6-4875-9678-67B3F130ADF6 | 1000     | printf "Hello world!"      |     |           | 2016-08-10 11:13:08 | 2016-08-10 11:13:28 |
| 441798A8-5F37-4147-B166-5A63C095B2DA | 1        | printf "Let me say hello:" |     |           | 2016-08-10 11:14:37 | 2016-08-10 11:14:37 |
+--------------------------------------+----------+----------------------------+-----+-----------+---------------------+---------------------+
```

### Run all joined tasks

To run all joined tasks execute the following command:

``` bash
$ php commander.phar run
```

The output will be printed to STDOUT and STDERR respectively. Use the `-q` option to omit the output. When a log file
path was specified, the output will also be logged.

### Remove all terminated tasks

To reduce the number of tasks in the database the terminated tasks can be purged of the database file:

``` bash
$ php commander.phar purge
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Daniel Schröder](https://github.com/pCoLaSD)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
