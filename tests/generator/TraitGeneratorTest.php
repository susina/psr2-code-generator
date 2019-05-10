<?php declare(strict_types=1);
namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\PhpTrait;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class TraitGeneratorTest extends TestCase
{
    public function testSignature()
    {
        $expected = "trait MyTrait\n{\n}\n";

        $trait = PhpTrait::create('MyTrait');
        $generator = new ModelGenerator();
        $code = $generator->generate($trait);

        $this->assertEquals($expected, $code);
    }
}
