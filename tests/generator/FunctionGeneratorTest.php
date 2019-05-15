<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\generator\builder\FunctionBuilder;
use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\PhpFunction;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpParameter;

/**
 * @group generator
 */
class FunctionGeneratorTest extends GeneratorTestCase
{
    public function testReferenceReturned()
    {
        $expected = "function & foo()\n{\n}\n";
    
        $method = PhpFunction::create('foo')->setReferenceReturned(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);
    
        $this->assertEquals($expected, $code);
    }
    
    public function testParameters()
    {
        $generator = new ModelGenerator($this->getConfig());
        
        $method = PhpFunction::create('foo')->addParameter(PhpParameter::create('bar'));
        $this->assertEquals("/**\n * @param \$bar\n */\nfunction foo(\$bar)\n{\n}\n", $generator->generate($method));
        
        $method = PhpFunction::create('foo')
            ->addParameter(PhpParameter::create('bar'))
            ->addParameter(PhpParameter::create('baz'));
        $this->assertEquals(
            "/**\n * @param \$bar\n * @param \$baz\n */\nfunction foo(\$bar, \$baz)\n{\n}\n",
            $generator->generate($method)
        );
    }
    
    public function testReturnType()
    {
        $expected = "/**\n * @return int\n */\nfunction foo(): int\n{\n}\n";
        $generator = new ModelGenerator($this->getConfig());

        $method = PhpFunction::create('foo')->setType('int');
        $this->assertEquals($expected, $generator->generate($method));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongClassThrowsException()
    {
        $generator = $this->getMockBuilder(ModelGenerator::class)->disableOriginalConstructor()->getMock();
        $wrongModel = PhpMethod::create('myMethod');
        $builder = new FunctionBuilder($generator);
        $builder->build($wrongModel);
    }
}
