<?php declare(strict_types=1);

namespace Susina\Codegen\Tests\Generator;

use Susina\Codegen\Generator\Builder\ConstantBuilder;
use Susina\Codegen\Generator\ModelGenerator;
use Susina\Codegen\Model\PhpConstant;
use Susina\Codegen\Model\PhpMethod;

class ConstantGeneratorTest extends GeneratorTestCase
{
    public function testValues(): void
    {
        $generator = new ModelGenerator($this->getConfig());

        $prop = PhpConstant::create('FOO')->setValue('string');
        $this->assertEquals('const FOO = \'string\';'."\n", $generator->generate($prop));

        $prop = PhpConstant::create('FOO')->setValue(300);
        $this->assertEquals('const FOO = 300;'."\n", $generator->generate($prop));

        $prop = PhpConstant::create('FOO')->setValue(162.5);
        $this->assertEquals('const FOO = 162.5;'."\n", $generator->generate($prop));

        $prop = PhpConstant::create('FOO')->setValue(true);
        $this->assertEquals('const FOO = true;'."\n", $generator->generate($prop));

        $prop = PhpConstant::create('FOO')->setValue(false);
        $this->assertEquals('const FOO = false;'."\n", $generator->generate($prop));

        $prop = PhpConstant::create('FOO')->setValue(null);
        $this->assertEquals('const FOO = null;'."\n", $generator->generate($prop));

        $prop = PhpConstant::create('FOO')->setValue(PhpConstant::create('BAR'));
        $this->assertEquals('const FOO = BAR;'."\n", $generator->generate($prop));

        $prop = PhpConstant::create('FOO')->setExpression("['bar' => 'baz']");
        $this->assertEquals('const FOO = [\'bar\' => \'baz\'];'."\n", $generator->generate($prop));
    }

    public function testWrongClassThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $generator = $this->getMockBuilder(ModelGenerator::class)->disableOriginalConstructor()->getMock();
        $wrongModel = PhpMethod::create('myMethod');
        $builder = new ConstantBuilder($generator);
        $builder->build($wrongModel);
    }
}
