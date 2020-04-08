<?php declare(strict_types=1);

namespace Susina\Codegen\Tests\Generator;

use Susina\Codegen\Generator\Builder\TraitBuilder;
use Susina\Codegen\Generator\ModelGenerator;
use Susina\Codegen\Model\PhpMethod;
use Susina\Codegen\Model\PhpTrait;

class TraitGeneratorTest extends GeneratorTestCase
{
    public function testSignature(): void
    {
        $expected = "trait MyTrait\n{\n}\n";

        $trait = PhpTrait::create('MyTrait');
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($trait);

        $this->assertEquals($expected, $code);
    }

    public function testInvalidModelThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $generator = $this->getMockBuilder(ModelGenerator::class)->disableOriginalConstructor()->getMock();
        $wrongModel = PhpMethod::create('myMethod');
        $builder = new TraitBuilder($generator);
        $builder->build($wrongModel);
    }
}
