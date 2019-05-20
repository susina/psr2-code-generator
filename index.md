---
title: Wellcome to PSR-2 Code Generator
description: Library to generate php code via fluent api, following PSR-2 standard
---

## Introduction

**psr2-code-generator** is a library to generate PHP code programmatically, via a nice fluent api.
It's a light version of the awesome [php-code-generator](https://github.com/gossi/php-code-generator) refactored to generate [PSR-2](https://www.php-fig.org/psr/psr-2/) code. Here are the main differences from the original library:

- PHP 7.2+
- full strict type
- no reverse engeneering (model generation from an existent file)
- no reflection (model generation from an instance of a class)

The generated code adheres to the following rules:

- PSR-2
- always strict type
- return type
- parameters type
- always PhpDoc comments 

## Installation

The library uses [Composer](https://getcomposer.org) as dependency manager. To install it run the following:

```bash
composer require cristianoc72/psr2-code-generator
```

## Workflow

1.  Create a *model*
2.  Create a *generator*
3.  Generate the code contained in the model

## Model

A model is a representation of your code, that you can create via the fluent api.

There are different types of models available which are explained in this section.

### Structured Models

Structured models are composites and can contain other models, these are:

-   `PhpClass` representing a class, with all its methods, properties, comments etc.
-   `PhpTrait` representing a trait with all its methods, properties etc.
-   `PhpInterface` representing an interface with all its method signatures, property definitions atc.

### Generateable Models

There is only a couple of models available which can be passed to a generator. These are the mentioned structured models + `PhpFunction` which represent a function.
So the list of generateable models is:

- `PhpClass`
- `PhpTrait`
- `PhpInterface`
- `PhpFunction`

### Part Models

Structured models can be composed of various members. Functions and methods can itself contain zero to many parameters. All parts are:

-   `PhpConstant` representing a constant
-   `PhpProperty` representing a property
-   `PhpMethod` representing a method
-   `PhpParameter` representing a function or method parameter

### Name vs. Namespace vs. Qualified Name ?

There can be a little struggle about the different names, which are name, namespace and qualified name. Every model has a name and generateable models additionally have a namespace and qualified name. The qualified name is a combination of namespace + name. Here is an overview:

Name | Tool
-------- | -----
Namespace | my\cool
Qualified Name | my\cool\Tool

### Create your first Class

Let's start with a simple example:

```php
<?php
use cristianoc72\codegen\model\PhpClass;

$class = new PhpClass();
$class->setQualifiedName('my\\cool\\Tool');
```

which will generate an empty class:

```php
<?php
namespace my\cool;

class Tool
{
}
```

### Adding a Constructor

It's better to have a constructor, so we add one:

```php
<?php
use cristianoc72\codegen\model\PhpClass;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpParameter;

\\ You can pass the name or the qualified name when you instantiate your model
$class = new PhpClass('my\\cool\\Tool');
$class
    ->setMethod(PhpMethod::create('__construct')
    ->addParameter(PhpParameter::create('target')
        ->setType('string')
        ->setDescription('Creates my Tool')
        )
    )
;
```

you can see the fluent API in action and the snippet above will generate:

```php
<?php
namespace my\cool;

class Tool
{

    /**
     * @param string $target Creates my Tool
     */
    public function __construct(string $target)
    {
    }
}
```

### Adding members

We've just learned how to pass a blank method, the constructor to the class. We can also add properties, constants and of course methods. Let's do so:

```php
<?php
use cristianoc72\codegen\model\PhpClass;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpParameter;
use cristianoc72\codegen\model\PhpProperty;
use cristianoc72\codegen\model\PhpConstant;

$class = PhpClass::create('my\\cool\\Tool')
    ->setMethod(PhpMethod::create('setDriver')
        ->addParameter(PhpParameter::create('driver')
            ->setType('string')
        )
        ->setType('bool')  // optional if you want return type
        ->setBody("$this->driver = $driver;
return true;"
            )
    )
    ->setProperty(PhpProperty::create('driver')
        ->setVisibility('private')
        ->setType('string')
    )
    ->setConstant(new PhpConstant('FOO', 'bar'))
;
```
will generate:

```php
<?php
namespace my\cool;

class Tool {

    /**
     */
    const FOO = 'bar';

    /**
     * @var string
     */
    private $driver;

    /**
     *
     * @param string $driver
     */
    public function setDriver(string $driver): bool
    {
        $this->driver = $driver;
        return true;
    }
}
```

### Declare use statements

When you put code inside a method there can be a reference to a class or interface, where you normally put the qualified name into a use statement. So here is how you do it:

```php
<?php
use cristianoc72\codegen\model\PhpClass;
use cristianoc72\codegen\model\PhpMethod;

$class = new PhpClass();
$class
    ->setName('Tool')
    ->setNamespace('my\\cool')
    ->setMethod(PhpMethod::create('__construct')
        ->setBody('$request = Request::createFromGlobals();')
    )
    ->declareUse('Symfony\\Component\\HttpFoundation\\Request')
;
```
which will create:

```php
<?php
namespace my\cool;

use Symfony\Component\HttpFoundation\Request;

class Tool {

    /**
     */
    public function __construct()
    {
        $request = Request::createFromGlobals();
    }
}
```

### Understanding Values

The models `PhpConstant`, `PhpParameter` and `PhpProperty` support values; all of them implement the `ValueInterface`. Each value object has a type, a value (of course) or an expression. There is a difference between values and expressions. Values refer to language primitives (`string`, `int`, `float`, `bool` and `null`). Additionally you can set a `PhpConstant` as value (the lib understands this as a library primitive ;-). If you want more complex control over the output, you can set an expression instead, which will be *generated as is*.

Some Examples:

```php
<?php
PhpProperty::create('foo')->setValue('hello world.');
// $foo = 'hello world.';

PhpProperty::create('foo')->setValue(300);
// $foo = 300;

PhpProperty::create('foo')->setValue(3.14);
// $foo = 3.14;

PhpProperty::create('foo')->setValue(false);
// $foo = false;

PhpProperty::create('foo')->setValue(null);
// $foo = null;

PhpProperty::create('foo')->setValue(PhpConstant::create('BAR'));
// $foo = BAR;

PhpProperty::create('foo')->setExpression('self::MY_CONST');
// $foo = self::MY_CONST;

PhpProperty::create('foo')->setExpression("['my' => 'array']");
// $foo = ['my' => 'array'];
```

For retrieving values there is a `hasValue()` method which returns `true` whether there is a value or an expression present. To be sure what is present there is also an `isExpression()` method which you can use as a second check:

```php
<?php

if ($prop->hasValue()) {
    if ($prop->isExpression()) {
        // do something with an expression
    } else {
        // do something with a value
    }
}
```

### Much, much more

The [API](api/index.html) has a lot more to offer and has almost full support for what you would expect to manipulate on each model, of course everything is fluent API.


## Generator

Now that your model is on the road, it's time to generate the code. To do this, a generator object is needed.

```php
<?php
use cristianoc72\codegen\generator\CodeGenerator;

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
    \\properties and methods according to the model
    .........
}
```

If you plan to save your generated code into a file, you can use `CodeFileGenerator` class instead, which adds the additional needed tags and the file level comment.

```php
<?php
use cristianoc72\codegen\generator\CodeFileGenerator;

$class = ..........; // code to create your model

$generator = new CodeFileGenerator();
$code = $generator->generate($class);
```
Now, our `$code` variable contains:

```php
<?php declare(strict(types=1);

/**
 * File level comment (e.g. copyrightinformations and so on)
 */

namespace \\my\\cool\\namespace;

/**
 * Class level commen
 * 
 * @author yourself
 */
class MyAwesomeClass extend FantasticFramework
{
    \\properties and methods according to the model
    .........
}
```

## Generator configuration

The package ships with two generators, which are configurable through an associative array as constructor parameter. Alternatively if you have a project that uses the same configuration over and over again, extend the `GeneratorConfig` class and pass an instance of it instead of the configuration array.

```php
<?php
use cristianoc72\codegen\generator\CodeGenerator;

// a) new code generator with options passed as array
$generator = new CodeGenerator([
  'generateEmptyDocblock' => true,
  ...
]);

// b) new code generator with options passed as object
$generator = new CodeGenerator(new MyGenerationConfig());
```

## CodeGenerators in detail

Generates code for a given model. It will also generate docblocks for all contained classes, methods, interfaces, etc. you have prior to generating the code.

-   Class: `cristianoc72\codegen\generator\CodeGenerator`
-   Options:

    Key | Type | Default Value | Description
    ----|------|---------------|------------
    generateEmptyDocblock | boolean | true |allows generation of empty docblocks
    enableSorting | boolean | true | Enables sorting
    useStatementSorting | boolean or string or Closure or Comparator | default | Sorting mechanism for use statements
    constantSorting | boolean or string or Closure or Comparator | default | Sorting mechanism for constants
    propertySorting | boolean or string or Closure or Comparator | default | Sorting mechanism for properties
    methodSorting | boolean or string or Closure or Comparator | default | Sorting mechanism for methods
    
    **Note 2**: For sorting ...

    -   ... a string will used to find a comparator with that name (at the moment there is only default).
    -   ... with a boolean you can disable sorting for a particular member
    -   ... you can pass in your own `\Closure` for comparison
    -   ... you can pass in a [Comparator](https://phootwork.github.io/lang/comparison/) for comparison
-   Example:

```php
<?php
use cristianoc72\codegen\generator\CodeGenerator;

// will set every option to true, because of the defaults
$generator = new CodeGenerator([
  'generateEmptyDocblock' => true,
  'enableSorting' => true
]);
$code = $generator->generate($myClass);
```
    
### CodeFileGenerator

Generates a complete php file with the given model inside. Especially useful when creating PSR-4 compliant code, which you are about to dump into a file. It extends the `CodeGenerator` and as such inherits all its benefits.

-   Class: `cristianoc72\codegen\generator\CodeFileGenerator`
-   Options: Same options as `CodeGenerator` plus:

    Key | Type | Default Value | Description
    ----|------|---------------|------------
    headerComment | null or string or Docblock | null | A comment, that will be put after the `<?php` statement
    headerDocblock | null or string or Docblock | null | A docblock that will be positioned after the possible header comment

-   Example:

```php
<?php
use cristianoc72\codegen\generator\CodeFileGenerator;

$generator = new CodeGenerator([
  'headerComment' => 'This will be placed at the top, woo',
  'headerDocblock' => 'Full documentation mode confirmed!',
]);
$code = $generator->generate($myClass);
```

## Template system for Code Bodies

It is useful to use some kind of template system to load the contents for your bodies. The template system can also be used to replace variables in the templates.

## Hack in Traits

Let's assume you generate a php class. This class will be used in your desired framework as it serves a specific purpose in there. It possible needs to fulfill an interface or some abstract methods and your generated code will also take care of this - wonderful. Now imagine the programmer wants to change the code your code generation tools created. Once you run the code generation tools again his changes probably got overwritten, which would be bad.

Here is the trick: First we declare the generated class as "host" class:

![image](images/hack-in-trait.png)

Your generated code will target the trait, where you can savely overwrite code. However, you must make sure the trait will be used from the host class and also generate the host class, if it doesn't exist. So here are the steps following this paradigm:

1.  Create the trait
2.  Check if the host class exists
    1.  if it exists, load it
    2.  if not, create it

3.  Add the trait to the host class
4.  Generate the host class code

That way, the host class will be user-land code and the developer can write his own code there. The code generation tools will keep that code intact, so it won't be destroyed when code generation tools run again. If you want to give the programmer more freedom offer him hook methods in the host class, that - if he wants to - can overwrite with his own logic.
