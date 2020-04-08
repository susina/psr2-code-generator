<?php declare(strict_types=1);

namespace Susina\Codegen\Tests\Generator;

use Susina\Codegen\Generator\Builder\InterfaceBuilder;
use Susina\Codegen\Generator\ModelGenerator;
use Susina\Codegen\Model\PhpInterface;
use Susina\Codegen\Model\PhpMethod;

class InterfaceGeneratorTest extends GeneratorTestCase
{
    public function testSignature(): void
    {
        $expected = "interface MyInterface\n{\n}\n";

        $interface = PhpInterface::create('MyInterface');
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($interface);

        $this->assertEquals($expected, $code);
    }

    public function testExtends(): void
    {
        $generator = new ModelGenerator($this->getConfig());

        $expected = "interface MyInterface extends \\Iterator\n{\n}\n";
        $interface = PhpInterface::create('MyInterface')->addInterface('\Iterator');
        $this->assertEquals($expected, $generator->generate($interface));

        $expected = "interface MyInterface extends \\Iterator, \\ArrayAccess\n{\n}\n";
        $interface = PhpInterface::create('MyInterface')->addInterface('\Iterator')->addInterface('\ArrayAccess');
        $this->assertEquals($expected, $generator->generate($interface));
    }

    public function testWrongClassThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $generator = $this->getMockBuilder(ModelGenerator::class)->disableOriginalConstructor()->getMock();
        $wrongModel = PhpMethod::create('myMethod');
        $builder = new InterfaceBuilder($generator);
        $builder->build($wrongModel);
    }
}
