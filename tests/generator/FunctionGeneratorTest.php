<?php declare(strict_types=1);
namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\PhpFunction;
use cristianoc72\codegen\model\PhpParameter;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class FunctionGeneratorTest extends TestCase
{
    public function testReferenceReturned()
    {
        $expected = "function & foo()\n{\n}\n";
    
        $method = PhpFunction::create('foo')->setReferenceReturned(true);
        $generator = new ModelGenerator();
        $code = $generator->generate($method);
    
        $this->assertEquals($expected, $code);
    }
    
    public function testParameters()
    {
        $generator = new ModelGenerator();
        
        $method = PhpFunction::create('foo')->addParameter(PhpParameter::create('bar'));
        $this->assertEquals("/**\n * @param \$bar\n */\nfunction foo(\$bar)\n{\n}\n", $generator->generate($method));
        
        $method = PhpFunction::create('foo')
            ->addParameter(PhpParameter::create('bar'))
            ->addParameter(PhpParameter::create('baz'));
        $this->assertEquals("/**\n * @param \$bar\n * @param \$baz\n */\nfunction foo(\$bar, \$baz)\n{\n}\n",
            $generator->generate($method)
        );
    }
    
    public function testReturnType()
    {
        $expected = "/**\n * @return int\n */\nfunction foo(): int\n{\n}\n";
        $generator = new ModelGenerator();

        $method = PhpFunction::create('foo')->setType('int');
        $this->assertEquals($expected, $generator->generate($method));
    }
}
