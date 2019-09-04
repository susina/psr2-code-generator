<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\generator\builder\PropertyBuilder;
use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\PhpConstant;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpProperty;

/**
 * @group generator
 */
class PropertyGeneratorTest extends GeneratorTestCase
{
    public function testPublic()
    {
        $expected = 'public $foo;'."\n";

        $prop = PhpProperty::create('foo');
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($prop);

        $this->assertEquals($expected, $code);
    }

    public function testProtected()
    {
        $expected = 'protected $foo;'."\n";

        $prop = PhpProperty::create('foo')->setVisibility(PhpProperty::VISIBILITY_PROTECTED);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($prop);

        $this->assertEquals($expected, $code);
    }

    public function testPrivate()
    {
        $expected = 'private $foo;'."\n";

        $prop = PhpProperty::create('foo')->setVisibility(PhpProperty::VISIBILITY_PRIVATE);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($prop);

        $this->assertEquals($expected, $code);
    }

    public function testStatic()
    {
        $expected = 'public static $foo;'."\n";

        $prop = PhpProperty::create('foo')->setStatic(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($prop);

        $this->assertEquals($expected, $code);
    }

    public function testValues()
    {
        $generator = new ModelGenerator($this->getConfig());

        $prop = PhpProperty::create('foo')->setValue('string');
        $this->assertEquals('public $foo = \'string\';'."\n", $generator->generate($prop));

        $prop = PhpProperty::create('foo')->setValue(300);
        $this->assertEquals('public $foo = 300;'."\n", $generator->generate($prop));

        $prop = PhpProperty::create('foo')->setValue(162.5);
        $this->assertEquals('public $foo = 162.5;'."\n", $generator->generate($prop));

        $prop = PhpProperty::create('foo')->setValue(true);
        $this->assertEquals('public $foo = true;'."\n", $generator->generate($prop));

        $prop = PhpProperty::create('foo')->setValue(false);
        $this->assertEquals('public $foo = false;'."\n", $generator->generate($prop));

        $prop = PhpProperty::create('foo')->setValue(null);
        $this->assertEquals('public $foo = null;'."\n", $generator->generate($prop));

        $prop = PhpProperty::create('foo')->setValue(PhpConstant::create('BAR'));
        $this->assertEquals('public $foo = BAR;'."\n", $generator->generate($prop));

        $prop = PhpProperty::create('foo')->setExpression("['bar' => 'baz']");
        $this->assertEquals('public $foo = [\'bar\' => \'baz\'];'."\n", $generator->generate($prop));
    }

    public function testWrongClassThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $generator = $this->getMockBuilder(ModelGenerator::class)->disableOriginalConstructor()->getMock();
        $wrongModel = PhpMethod::create('myMethod');
        $builder = new PropertyBuilder($generator);
        $builder->build($wrongModel);
    }
}
