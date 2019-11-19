<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\generator\builder\TraitBuilder;
use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpTrait;

/**
 * @group generator
 */
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
