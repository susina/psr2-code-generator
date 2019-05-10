<?php declare(strict_types=1);
namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\PhpInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class InterfaceGeneratorTest extends TestCase
{
    public function testSignature()
    {
        $expected = "interface MyInterface\n{\n}\n";

        $interface = PhpInterface::create('MyInterface');
        $generator = new ModelGenerator();
        $code = $generator->generate($interface);
        
        $this->assertEquals($expected, $code);
    }
    
    public function testExtends()
    {
        $generator = new ModelGenerator();
        
        $expected = "interface MyInterface extends \Iterator\n{\n}\n";
        $interface = PhpInterface::create('MyInterface')->addInterface('\Iterator');
        $this->assertEquals($expected, $generator->generate($interface));
        
        $expected = "interface MyInterface extends \Iterator, \ArrayAccess\n{\n}\n";
        $interface = PhpInterface::create('MyInterface')->addInterface('\Iterator')->addInterface('\ArrayAccess');
        $this->assertEquals($expected, $generator->generate($interface));
    }
}
