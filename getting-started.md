---
title: Getting started
description: Getting started guide
---

There are two things you need to generate code.

1.  A model that contains the code structure
    -   PhpClass
    -   PhpInterface
    -   PhpTrait
    -   PhpFunction

2.  A generator
    -   CodeGenerator
    -   CodeFileGenerator

You can create these models and push all the data using a fluent API or read from existing code through reflection.

Here are two examples for each of those.

## Generate Code

1)  Simple:

```php
<?php
    use cristianoc72\codegen\generator\CodeGenerator;
    use cristianoc72\codegen\model\PhpClass;
    use cristianoc72\codegen\model\PhpMethod;
    use cristianoc72\codegen\model\PhpParameter;
    
    $class = new PhpClass();
    $class
        ->setQualifiedName('my\\cool\\Tool')
        ->setMethod(PhpMethod::create('__construct')
        ->addParameter(PhpParameter::create('target')
            ->setType('string')
            ->setDescription('Creates my Tool')
            )
        )
    ;

    $generator = new CodeGenerator();
    $code = $generator->generate($class);
```
will generate:

```php
<?php
    namespace my\cool;

    class Tool {

        /**
         *
         * @param $target string Creates my Tool
         */
        public function __construct($target)
        {
        }
    }
```

2)  From File:

```php
<?php
    use cristianoc72\codegen\generator\CodeGenerator;
    use cristianoc72\codegen\model\PhpClass;

    $class = PhpClass::fromFile('path/to/class.php');

    $generator = new CodeGenerator();
    $code = $generator->generate($class);
```

3)  From Reflection:

```php
<?php
    use cristianoc72\codegen\generator\CodeGenerator;
    use cristianoc72\codegen\model\PhpClass;

    $reflection = new \ReflectionClass('MyClass');
    $class = PhpClass::fromReflection($reflection->getFileName());

    $generator = new CodeGenerator();
    $code = $generator->generate($class);
```