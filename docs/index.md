# Welcome to PSR-2 Code Generator

**psr2-code-generator** is a library to generate PHP code programmatically, via a nice fluent api.
It's a light version of the awesome [php-code-generator](https://github.com/gossi/php-code-generator) refactored
to generate [PSR-1](https://www.php-fig.org/psr/psr-1/) and [PSR-2](https://www.php-fig.org/psr/psr-2/) code.

Here are the main differences from the original library:

  - PHP 7.2+
  - full strict type
  - no reverse engeneering (model generation from an existent file)
  - no reflection (model generation from an instance of a class)
  - PHP 7.4 typed properties (optional)

The generated code adheres to the following rules:

  - PSR-1 and PSR-2
  - always strict type
  - return types
  - parameters types
  - always PhpDoc comments

## Installation

The library uses [Composer](https://getcomposer.org) as dependency manager. To install it run the following:

```bash
composer require susina/psr2-code-generator
```

## Next Step

Start reading the [User Guide](user_guide.md)

## Issues

If you find an issue or need some help, please open a [ticket on Github repository](https://github.com/cristianoc72/psr2-code-generator/issues).

## License

**psr2-code-generator** is released under the [Apache2](https://www.apache.org/licenses/LICENSE-2.0) license.
A full copy of the license is shipped with the code into the file [LICENSE](https://github.com/cristianoc72/psr2-code-generator/blob/master/LICENSE).
