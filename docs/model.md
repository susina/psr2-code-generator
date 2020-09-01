# Model

A model is a representation of your code, that you can create via the fluent api.

There are different types of models available which are explained in this section.

## Structured Generateable Models

Structured models are composites and can contain other models. Structured models implement the `GenerateableInterface` and
can be passed to a generator. These are: 

-   `PhpClass` representing a class, with all its methods, properties, comments etc.
-   `PhpTrait` representing a trait with all its methods, properties etc.
-   `PhpInterface` representing an interface with all its method signatures, property definitions atc.

## Part Models

Structured models can be composed of various members. Functions and methods can itself contain zero to many parameters.
All parts are:

-   `PhpConstant` representing a constant
-   `PhpProperty` representing a property
-   `PhpMethod` representing a method
-   `PhpParameter` representing a function or method parameter

## Name vs. Namespace vs. Qualified Name ?

There can be a little struggle about the different names, which are name, namespace and qualified name. Every model has
a name and generateable models additionally have a namespace and qualified name. The qualified name is a combination of
namespace + name. Here is an overview:

Name | Tool
-------- | -----
Namespace | my\cool
Qualified Name | my\cool\Tool

## Create your first Class

Let's start with a simple example:

```php
<?php
use Susina\Codegen\Model\PhpClass;

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

## Adding a Constructor

It's better to have a constructor, so we add one:

```php
<?php
use Susina\Codegen\Model\PhpClass;
use Susina\Codegen\Model\PhpMethod;
use Susina\Codegen\Model\PhpParameter;

// You can pass the name or the qualified name when you instantiate your model
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

## Adding members

We've just learned how to pass a blank method, the constructor to the class. We can also add properties, constants and
of course methods. Let's do so:

```php
<?php
use Susina\Codegen\Model\PhpClass;
use Susina\Codegen\Model\PhpMethod;
use Susina\Codegen\Model\PhpParameter;
use Susina\Codegen\Model\PhpProperty;
use Susina\Codegen\Model\PhpConstant;

$class = PhpClass::create('my\\cool\\Tool')
    ->setMethod(PhpMethod::create('setDriver')
        ->addParameter(PhpParameter::create('driver')
            ->setType('string')
        )
        ->setType('bool')  // optional if you want return type
        ->setBody("\$this->driver = \$driver;
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
     * @return bool
     */
    public function setDriver(string $driver): bool
    {
        $this->driver = $driver;
        return true;
    }
}
```

Let's add some docblock comments, too:

```php
<?php
use Susina\Codegen\Model\PhpClass;
use Susina\Codegen\Model\PhpMethod;
use Susina\Codegen\Model\PhpParameter;
use Susina\Codegen\Model\PhpProperty;
use Susina\Codegen\Model\PhpConstant;

$class = PhpClass::create('my\\cool\\Tool')
    ->setMultilineDescription(["The fantastic Tool class.", "", "@author John Smith"])
    ->setMethod(PhpMethod::create('setDriver')
        ->setDescription("Set the specific driver")
        ->addParameter(PhpParameter::create('driver')
            ->setType('string')
            ->setDescription("The driver")
        )
        ->setType('bool')
        ->setTypeDescription("If everything is ok")
        ->setBody("\$this->driver = \$driver;
return true;"
            )
    )
    ->setProperty(PhpProperty::create('driver')
        ->setVisibility('private')
        ->setType('string')
        ->setDescription("The driver")
    )
    ->setConstant((new PhpConstant('FOO', 'bar'))
        ->setDescription("The FOO constant")
    )
;
```
will generate:

```php
<?php
namespace my\cool;

/**
* The fantastic Tool class
 * 
 * @author John Smith
 */
class Tool {

    /**
     * The FOO constant 
     */
    const FOO = 'bar';

    /**
     * The driver
     * 
     * @var string
     */
    private $driver;

    /**
     * Set the specific driver
     * 
     * @param string $driver
     * @return bool If everything is ok
     */
    public function setDriver(string $driver): bool
    {
        $this->driver = $driver;
        return true;
    }
}
```

## Declare use statements

When you put code inside a method there can be a reference to a class or interface, where you normally put the qualified
name into a use statement. So here is how you do it:

```php
<?php
use Susina\Codegen\Model\PhpClass;
use Susina\Codegen\Model\PhpMethod;

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

## Understanding Values

The models `PhpConstant`, `PhpParameter` and `PhpProperty` support values; all of them implement the `ValueInterface`.
Each value object has a type, a value (of course) or an expression. There is a difference between values and expressions.
Values refer to language primitives (`string`, `int`, `float`, `bool` and `null`). Additionally you can set a `PhpConstant`
as value (the lib understands this as a library primitive ;-). If you want more complex control over the output,
you can set an expression instead, which will be _generated as is_.

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

For retrieving values there is a `hasValue()` method which returns `true` whether there is a value or an expression present.
To be sure what is present there is also an `isExpression()` method which you can use as a second check:

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

## More about types

### Returning `$this` for fluent interface

When you write a class with fluent interface, you want to specify that a method returns `$this` or the class itself.
You can pass the docblock notation to the `setType` method. I.e.

```php
<?php

$method = new PhpMethod('setDriver');
$method
    ->setType('$this|FileManager')
    ->setTypeDescription('For fluent interface')
;
```
and it'll result:

```php
<?php

............

/**
 * @return $this|FileManager For fluent interface
 */
public function setDriver(): FileManager
{}
```

If your method is part of a class, the type of `$this` is auto-discovered:

```php
<?php

$class = new PhpClass('FileManager');
$method = PhpMethod::create('setDriver')
    ->setType('$this')
    ->setTypeDescription('For fluent interface')
;

$class->setMethod($method);
```

It'll result:

```php
<?php

...............

class FileManager
{
    /**
     * @return $this|FileManager For fluent interface
     */
    public function setDriver(): FileManager
    {}
}
```

### Nullable types

When you want to define a nullable type, you can use both docblock notation (i.e. `int|null`) and Php one (i.e. `?int`):

```php
<?php

// Docblock notation
$method = PhpMethod::create('fileSize')->setType('int|null');

// or PHP notation
$method1 = PhpMethod::create('fileDescription')->setType('?string');
```

The result is:

```php
<?php

...................

/**
 * @return int|null
 */
public function fileSize(): ?int
{}

/**
 * @return string|null
 */
public function fileDescription(): ?string
{}
```

## Much, much more

The [API](api/index.html) has a lot more to offer and has almost full support for what you would expect to manipulate on each model, of course everything is fluent API.
