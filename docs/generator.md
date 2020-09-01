## Generator

Now that your model is on the road, it's time to generate the code. To do this, a generator object is needed.

```php
<?php
use Susina\Codegen\Generator\CodeGenerator;

$class = ..........; // code to create your model

$generator = new CodeGenerator();
$code = $generator->generate($class);
```
Now, our `$code` variable contains a string with the generated code, according to the previously created model:

```php
namespace \\my\\cool\\namespace;

/**
 * Class level commen
 * 
 * @author yourself
 */
class MyAwesomeClass extend FantasticFramework
{
    //properties and methods according to the model
    .........
}
```

If you plan to save your generated code into a file, you can use `CodeFileGenerator` class instead, which adds the
additional needed tags and the file level comment.

```php
<?php
use Susina\Codegen\Generator\CodeFileGenerator;

$class = ..........; // code to create your model

$generator = new CodeFileGenerator();
$code = $generator->generate($class);
```
Now, our `$code` variable contains:

```php
<?php declare(strict_types=1);

/**
 * File level comment (e.g. copyright information and so on)
 */

namespace \\my\\cool\\namespace;

/**
 * Class level comment
 * 
 * @author yourself
 */
class MyAwesomeClass extend FantasticFramework
{
    //properties and methods according to the model
    .........
}
```

## Generator configuration

The package ships with two generators, which are configurable through an associative array as constructor parameter.
Alternatively if you have a project that uses the same configuration over and over again, extend the `GeneratorConfig`
class and pass an instance of it instead of the configuration array.

```php
<?php
use Susina\Codegen\Generator\CodeGenerator;

// a) new code generator with options passed as array
$generator = new CodeGenerator([
  'generateEmptyDocblock' => true,
  ...
]);

// b) new code generator with options passed as object
$generator = new CodeGenerator(new MyGenerationConfig());
```

## CodeGenerators in detail

Generates code for a given model. It will also generate docblocks for all contained classes, methods, interfaces, etc.
you have prior to generating the code.

-   Class: `Susina\Codegen\Generator\CodeGenerator`
-   Options:

    Key | Type | Default Value | Description
    ----|------|---------------|------------
    generateEmptyDocblock | boolean | false | Allows generation of empty docblocks
    enableSorting | boolean | true | Enables sorting
    useStatementSorting | boolean or string or Closure or Comparator | default | Sorting mechanism for use statements
    constantSorting | boolean or string or Closure or Comparator | default | Sorting mechanism for constants
    propertySorting | boolean or string or Closure or Comparator | default | Sorting mechanism for properties
    methodSorting | boolean or string or Closure or Comparator | default | Sorting mechanism for methods
    php74Properties| boolean | false | If true, generate classes with typed properties
    
    !!! note "For sorting..."

        -   ... a string will used to find a comparator with that name (at the moment there is only default).
        -   ... with a boolean you can disable sorting for a particular member
        -   ... you can pass in your own `\Closure` for comparison
        -   ... you can pass in a [Comparator](https://phootwork.github.io/lang/comparison/) for comparison
        
Example:

```php
<?php
use Susina\Codegen\Generator\CodeGenerator;

// will set every option to true, because of the defaults
$generator = new CodeGenerator([
  'generateEmptyDocblock' => true,
  'enableSorting' => true
]);
$code = $generator->generate($myClass);
```
    
### CodeFileGenerator

Generates a complete php file with the given model inside. Especially useful when creating PSR-4 compliant code,
which you are about to dump into a file. It extends the `CodeGenerator` and as such inherits all its benefits.

-   Class: `Susina\Codegen\Generator\CodeFileGenerator`
-   Options: Same options as `CodeGenerator` plus:

    Key | Type | Default Value | Description
    ----|------|---------------|------------
    headerComment | null or string or Docblock | null | A comment, that will be put after the `<?php` statement
    headerDocblock | null or string or Docblock | null | A docblock that will be positioned after the possible header comment

-   Example:

```php
<?php
use Susina\Codegen\Generator\CodeFileGenerator;

$generator = new CodeGenerator([
  'headerComment' => 'This will be placed at the top, woo',
  'headerDocblock' => 'Full documentation mode confirmed!',
]);
$code = $generator->generate($myClass);
```

## PHP 7.4+ typed class properties

You can generate PHP 7.4+ classes with typed properties. Just set `php74Properties` configuration attribute to `true`,
when you instantiate your generator:

```php
<?php
use Susina\Codegen\Generator\CodeGenerator;

$class = PhpClass::create('MyAwesomeClass')
    ->setDescription('Class level comment')
    ->setNamespace('\my\cool\namespace')
    ->setProperty(PhpProperty::create('driver')
        ->setType('string')->setDescription('The driver')
    )
;

$generator = new CodeGenerator(['php74Properties' => true]);
$code = $generator->generate($class);
```
It results in:

```php
<?php

namespace \my\cool\namespace;

/**
 * Class level comment
 */
class MyAwesomeClass
{
    /**
     * @var string The driver
     */
    protected string $driver;
}
```

## Template system for Code Bodies

It is useful to use some kind of template system to load the contents for your method bodies,
[Mustache](https://github.com/bobthecow/mustache.php), [Twig](https://twig.symfony.com/) or else. 
The template system can also be used to replace variables in the templates.
