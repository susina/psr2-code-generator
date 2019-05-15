<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\generator\builder\MethodBuilder;
use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpParameter;
use cristianoc72\codegen\model\PhpProperty;

/**
 * @group generator
 */
class MethodGeneratorTest extends GeneratorTestCase
{
    public function testPublic()
    {
        $expected = "public function foo()\n{\n}\n";
    
        $method = PhpMethod::create('foo');
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);
    
        $this->assertEquals($expected, $code);
    }
    
    public function testProtected()
    {
        $expected = "protected function foo()\n{\n}\n";

        $method = PhpMethod::create('foo')->setVisibility(PhpMethod::VISIBILITY_PROTECTED);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);

        $this->assertEquals($expected, $code);
    }
    
    public function testPrivate()
    {
        $expected = "private function foo()\n{\n}\n";
    
        $method = PhpMethod::create('foo')->setVisibility(PhpMethod::VISIBILITY_PRIVATE);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);
    
        $this->assertEquals($expected, $code);
    }
    
    public function testStatic()
    {
        $expected = "public static function foo()\n{\n}\n";
    
        $method = PhpMethod::create('foo')->setStatic(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);
    
        $this->assertEquals($expected, $code);
    }
    
    public function testAbstract()
    {
        $expected = "abstract public function foo();\n";
    
        $method = PhpMethod::create('foo')->setAbstract(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);
    
        $this->assertEquals($expected, $code);
    }
    
    public function testReferenceReturned()
    {
        $expected = "public function & foo()\n{\n}\n";
    
        $method = PhpMethod::create('foo')->setReferenceReturned(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);
    
        $this->assertEquals($expected, $code);
    }
    
    public function testParameters()
    {
        $generator = new ModelGenerator($this->getConfig());
        
        $method = PhpMethod::create('foo')->addParameter(PhpParameter::create('bar'));
        $this->assertEquals("/**\n * @param \$bar\n */\npublic function foo(\$bar)\n{\n}\n", $generator->generate($method));
        
        $method = PhpMethod::create('foo')
            ->addParameter(PhpParameter::create('bar'))
            ->addParameter(PhpParameter::create('baz'));
        $this->assertEquals(
            "/**\n * @param \$bar\n * @param \$baz\n */\npublic function foo(\$bar, \$baz)\n{\n}\n",
            $generator->generate($method)
        );
    }
    
    public function testReturnType()
    {
        $expected = "/**\n * @return int\n */\npublic function foo(): int\n{\n}\n";
        $generator = new ModelGenerator($this->getConfig());

        $method = PhpMethod::create('foo')->setType('int');
        $this->assertEquals($expected, $generator->generate($method));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongClassThrowsException()
    {
        $generator = $this->getMockBuilder(ModelGenerator::class)->disableOriginalConstructor()->getMock();
        $wrongModel = PhpProperty::create('myMethod');
        $builder = new MethodBuilder($generator);
        $builder->build($wrongModel);
    }
}
