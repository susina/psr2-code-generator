<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\generator\builder\ParameterBuilder;
use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\PhpConstant;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpParameter;

/**
 * @group generator
 *
 * @internal
 * @coversNothing
 */
class ParameterGeneratorTest extends GeneratorTestCase
{
    public function testPassedByReference(): void
    {
        $expected = '&$foo';

        $param = PhpParameter::create('foo')->setPassedByReference(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($param);

        $this->assertEquals($expected, $code);
    }

    public function testTypeHints(): void
    {
        $generator = new ModelGenerator($this->getConfig());

        $param = PhpParameter::create('foo')->setType('Request');
        $this->assertEquals('Request $foo', $generator->generate($param));

        $param = PhpParameter::create('foo')->setType('string');
        $this->assertEquals('string $foo', $generator->generate($param));

        $param = PhpParameter::create('foo')->setType('int');
        $this->assertEquals('int $foo', $generator->generate($param));

        $param = PhpParameter::create('foo')->setType('integer');
        $this->assertEquals('int $foo', $generator->generate($param));

        $param = PhpParameter::create('foo')->setType('float');
        $this->assertEquals('float $foo', $generator->generate($param));

        $param = PhpParameter::create('foo')->setType('double');
        $this->assertEquals('float $foo', $generator->generate($param));

        $param = PhpParameter::create('foo')->setType('bool');
        $this->assertEquals('bool $foo', $generator->generate($param));

        $param = PhpParameter::create('foo')->setType('boolean');
        $this->assertEquals('bool $foo', $generator->generate($param));

        $param = PhpParameter::create('foo')->setType('mixed');
        $this->assertEquals('$foo', $generator->generate($param));

        $param = PhpParameter::create('foo')->setType('object');
        $this->assertEquals('$foo', $generator->generate($param));

        $param = PhpParameter::create('foo')->setType('resource');
        $this->assertEquals('$foo', $generator->generate($param));
    }

    public function testValues(): void
    {
        $generator = new ModelGenerator($this->getConfig());

        $prop = PhpParameter::create('foo')->setValue('string');
        $this->assertEquals('$foo = \'string\'', $generator->generate($prop));

        $prop = PhpParameter::create('foo')->setValue(300);
        $this->assertEquals('$foo = 300', $generator->generate($prop));

        $prop = PhpParameter::create('foo')->setValue(162.5);
        $this->assertEquals('$foo = 162.5', $generator->generate($prop));

        $prop = PhpParameter::create('foo')->setValue(true);
        $this->assertEquals('$foo = true', $generator->generate($prop));

        $prop = PhpParameter::create('foo')->setValue(false);
        $this->assertEquals('$foo = false', $generator->generate($prop));

        $prop = PhpParameter::create('foo')->setValue(null);
        $this->assertEquals('$foo = null', $generator->generate($prop));

        $prop = PhpParameter::create('foo')->setValue(PhpConstant::create('BAR'));
        $this->assertEquals('$foo = BAR', $generator->generate($prop));

        $prop = PhpParameter::create('foo')->setExpression("['bar' => 'baz']");
        $this->assertEquals('$foo = [\'bar\' => \'baz\']', $generator->generate($prop));
    }

    public function testWrongClassThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $generator = $this->getMockBuilder(ModelGenerator::class)->disableOriginalConstructor()->getMock();
        $wrongModel = PhpMethod::create('myMethod');
        $builder = new ParameterBuilder($generator);
        $builder->build($wrongModel);
    }
}
