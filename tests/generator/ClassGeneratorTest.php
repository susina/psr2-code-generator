<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\config\GeneratorConfig;
use cristianoc72\codegen\generator\builder\ClassBuilder;
use cristianoc72\codegen\generator\CodeFileGenerator;
use cristianoc72\codegen\generator\CodeGenerator;
use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\PhpClass;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpProperty;
use cristianoc72\codegen\model\PhpTrait;

/**
 * @group generator
 */
class ClassGeneratorTest extends GeneratorTestCase
{
    public function testSignature(): void
    {
        $expected = "class MyClass\n{\n}\n";

        $class = PhpClass::create('MyClass');
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($class);

        $this->assertEquals($expected, $code);
    }

    public function testAbstract(): void
    {
        $expected = "abstract class MyClass\n{\n}\n";

        $class = PhpClass::create('MyClass')->setAbstract(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($class);

        $this->assertEquals($expected, $code);
    }

    public function testFinal(): void
    {
        $expected = "final class MyClass\n{\n}\n";

        $class = PhpClass::create('MyClass')->setFinal(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($class);

        $this->assertEquals($expected, $code);
    }

    public function testInterfaces(): void
    {
        $generator = new ModelGenerator($this->getConfig());

        $expected = "class MyClass implements \Iterator\n{\n}\n";
        $class = PhpClass::create('MyClass')->addInterface('\Iterator');
        $this->assertEquals($expected, $generator->generate($class));

        $expected = "class MyClass implements \Iterator, \ArrayAccess\n{\n}\n";
        $class = PhpClass::create('MyClass')->addInterface('\Iterator')->addInterface('\ArrayAccess');
        $this->assertEquals($expected, $generator->generate($class));
    }

    public function testParent(): void
    {
        $expected = "class MyClass extends MyParent\n{\n}\n";

        $class = PhpClass::create('MyClass')->setParentClassName('MyParent');
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($class);

        $this->assertEquals($expected, $code);
    }

    public function testUseStatements(): void
    {
        $class = new PhpClass('Foo\\FooBar');
        $class->addUseStatement('Bam\\Baz');

        $codegen = new CodeFileGenerator(['generateEmptyDocblock' => false]);
        $code = $codegen->generate($class);

        $this->assertEquals($this->getGeneratedContent('FooBar.php'), $code);

        $class = new PhpClass('Foo\\FooBarWithAlias');
        $class->addUseStatement('Bam\\Baz', 'BamBaz');

        $codegen = new CodeFileGenerator(['generateEmptyDocblock' => false]);
        $code = $codegen->generate($class);

        $this->assertEquals($this->getGeneratedContent('FooBarWithAlias.php'), $code);

        $class = new PhpClass('Foo');
        $class->addUseStatement('Bar');

        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($class);
        $expected = "class Foo\n{\n}\n";

        $this->assertEquals($expected, $code);
    }

    public function testABClass(): void
    {
        $class = PhpClass::create()
            ->setName('ABClass')
            ->setMethod(PhpMethod::create('a'))
            ->setMethod(PhpMethod::create('b'))
            ->setProperty(PhpProperty::create('a'))
            ->setProperty(PhpProperty::create('b'))
            ->setConstantByName('a', 'foo')
            ->setConstantByName('b', 'bar');

        $modelGenerator = new ModelGenerator($this->getConfig());
        $modelCode = $modelGenerator->generate($class);
        $this->assertEquals($this->getGeneratedContent('ABClass.php'), $modelCode);
        $generator = new CodeGenerator();
        $code = $generator->generate($class);
        $this->assertEquals($modelCode, $code);

        $config = $this->createMock(GeneratorConfig::class);
        $config->method('getGenerateEmptyDocblock')->willReturn(true);

        $modelGenerator = new ModelGenerator($config);
        $modelCode = $modelGenerator->generate($class);
        $this->assertEquals($this->getGeneratedContent('ABClassWithComments.php'), $modelCode);
        $generator = new CodeGenerator(['generateEmptyDocblock' => true]);
        $code = $generator->generate($class);
        $this->assertEquals($modelCode, $code);
    }
    
    public function testRequireTraitsClass(): void
    {
        $class = PhpClass::create('RequireTraitsClass')
            ->addRequiredFile('FooBar.php')
            ->addRequiredFile('ABClass.php')
            ->addTrait(new PhpTrait('Iterator'));

        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($class);
        $this->assertEquals($this->getGeneratedContent('RequireTraitsClass.php'), $code);
    }

    public function testWrongClassThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $generator = $this->getMockBuilder(ModelGenerator::class)->disableOriginalConstructor()->getMock();
        $wrongModel = PhpMethod::create('myMethod');
        $builder = new ClassBuilder($generator);
        $builder->build($wrongModel);
    }
}
