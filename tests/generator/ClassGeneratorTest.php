<?php
namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\generator\CodeFileGenerator;
use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\PhpClass;
use cristianoc72\codegen\tests\Fixtures;
use cristianoc72\codegen\tests\parts\TestUtils;
use cristianoc72\codegen\generator\CodeGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class ClassGeneratorTest extends TestCase
{
    use TestUtils;

    public function testSignature()
    {
        $expected = "class MyClass\n{\n}\n";

        $class = PhpClass::create('MyClass');
        $generator = new ModelGenerator();
        $code = $generator->generate($class);

        $this->assertEquals($expected, $code);
    }
    
    public function testAbstract()
    {
        $expected = "abstract class MyClass\n{\n}\n";
    
        $class = PhpClass::create('MyClass')->setAbstract(true);
        $generator = new ModelGenerator();
        $code = $generator->generate($class);
    
        $this->assertEquals($expected, $code);
    }
    
    public function testFinal()
    {
        $expected = "final class MyClass\n{\n}\n";
    
        $class = PhpClass::create('MyClass')->setFinal(true);
        $generator = new ModelGenerator();
        $code = $generator->generate($class);
    
        $this->assertEquals($expected, $code);
    }
    
    public function testInterfaces()
    {
        $generator = new ModelGenerator();
    
        $expected = "class MyClass implements \Iterator\n{\n}\n";
        $class = PhpClass::create('MyClass')->addInterface('\Iterator');
        $this->assertEquals($expected, $generator->generate($class));
    
        $expected = "class MyClass implements \Iterator, \ArrayAccess\n{\n}\n";
        $class = PhpClass::create('MyClass')->addInterface('\Iterator')->addInterface('\ArrayAccess');
        $this->assertEquals($expected, $generator->generate($class));
    }

    public function testParent()
    {
        $expected = "class MyClass extends MyParent\n{\n}\n";
    
        $class = PhpClass::create('MyClass')->setParentClassName('MyParent');
        $generator = new ModelGenerator();
        $code = $generator->generate($class);
    
        $this->assertEquals($expected, $code);
    }

    public function testUseStatements()
    {
        $class = new PhpClass('Foo\\Bar');
        $class->addUseStatement('Bam\\Baz');
    
        $codegen = new CodeFileGenerator(['generateDocblock' => false, 'generateEmptyDocblock' => false]);
        $code = $codegen->generate($class);
    
        $this->assertEquals($this->getGeneratedContent('FooBar.php'), $code);
    
        $class = new PhpClass('Foo\\Bar');
        $class->addUseStatement('Bam\\Baz', 'BamBaz');
    
        $codegen = new CodeFileGenerator(['generateDocblock' => false, 'generateEmptyDocblock' => false]);
        $code = $codegen->generate($class);
    
        $this->assertEquals($this->getGeneratedContent('FooBarWithAlias.php'), $code);
        
        $class = new PhpClass('Foo');
        $class->addUseStatement('Bar');
        
        $generator = new ModelGenerator();
        $code = $generator->generate($class);
        $expected = "class Foo\n{\n}\n";
        
        $this->assertEquals($expected, $code);
    }

    public function testABClass()
    {
        $class = Fixtures::createABClass();
    
        $modelGenerator = new ModelGenerator();
        $modelCode = $modelGenerator->generate($class);
        $this->assertEquals($this->getGeneratedContent('ABClass.php'), $modelCode);
        $generator = new CodeGenerator(['generateDocblock' => false]);
        $code = $generator->generate($class);
        $this->assertEquals($modelCode, $code);
        
        $modelGenerator = new ModelGenerator(['generateDocblock' => true]);
        $modelCode = $modelGenerator->generate($class);
        $this->assertEquals($this->getGeneratedContent('ABClassWithComments.php'), $modelCode);
        $generator = new CodeGenerator(['generateDocblock' => true]);
        $code = $generator->generate($class);
        $this->assertEquals($modelCode, $code);
    }
    
    public function testRequireTraitsClass()
    {
        $class = PhpClass::create('RequireTraitsClass')
            ->addRequiredFile('FooBar.php')
            ->addRequiredFile('ABClass.php')
            ->addTrait('Iterator');
        
        $generator = new ModelGenerator();
        $code = $generator->generate($class);
        $this->assertEquals($this->getGeneratedContent('RequireTraitsClass.php'), $code);
    }
}
