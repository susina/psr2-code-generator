# PSR-2 Code Generator

![](https://github.com/cristianoc72/psr2-code-generator/workflows/Tests/badge.svg)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)
[![Maintainability](https://api.codeclimate.com/v1/badges/aa8d57cef69166ace691/maintainability)](https://codeclimate.com/github/cristianoc72/psr2-code-generator/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/aa8d57cef69166ace691/test_coverage)](https://codeclimate.com/github/cristianoc72/psr2-code-generator/test_coverage)

Psr2-code-generator is a library to generate PHP code programmatically, via a nice fluent api.
This library is a light version of the awesome https://github.com/gossi/php-code-generator, refactored to generate PSR-2 code.

Differences and restrictions from the original library:
- php 7.2+ strictly typed
- generate PSR-2 code **ONLY**
- generate only php 7.1+ code (strict types)
- no reflection nor reverse engeneering

## Installation

Install via Composer:

```
composer require cristianoc72/psr2-code-generator
```

## Documentation

Documentation is available at https://cristianoc72.github.io/psr2-code-generator

## Contributing

Feel free to fork and submit a pull request. Don't forget the tests and PSR-2 standard, of course.
