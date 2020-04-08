<?php declare(strict_types=1);

namespace Susina\Codegen\Tests\Generator;

use Susina\Codegen\Generator\Builder\MethodBuilder;
use Susina\Codegen\Generator\ModelGenerator;
use Susina\Codegen\Model\PhpMethod;
use Susina\Codegen\Model\PhpParameter;
use Susina\Codegen\Model\PhpProperty;

class MethodGeneratorTest extends GeneratorTestCase
{
    public function testPublic(): void
    {
        $expected = "public function foo()\n{\n}\n";

        $method = PhpMethod::create('foo');
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);

        $this->assertEquals($expected, $code);
    }

    public function testProtected(): void
    {
        $expected = "protected function foo()\n{\n}\n";

        $method = PhpMethod::create('foo')->setVisibility(PhpMethod::VISIBILITY_PROTECTED);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);

        $this->assertEquals($expected, $code);
    }

    public function testPrivate(): void
    {
        $expected = "private function foo()\n{\n}\n";

        $method = PhpMethod::create('foo')->setVisibility(PhpMethod::VISIBILITY_PRIVATE);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);

        $this->assertEquals($expected, $code);
    }

    public function testStatic(): void
    {
        $expected = "public static function foo()\n{\n}\n";

        $method = PhpMethod::create('foo')->setStatic(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);

        $this->assertEquals($expected, $code);
    }

    public function testAbstract(): void
    {
        $expected = "abstract public function foo();\n";

        $method = PhpMethod::create('foo')->setAbstract(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);

        $this->assertEquals($expected, $code);
    }

    public function testReferenceReturned(): void
    {
        $expected = "public function & foo()\n{\n}\n";

        $method = PhpMethod::create('foo')->setReferenceReturned(true);
        $generator = new ModelGenerator($this->getConfig());
        $code = $generator->generate($method);

        $this->assertEquals($expected, $code);
    }

    public function testParameters(): void
    {
        $generator = new ModelGenerator($this->getConfig());

        $method = PhpMethod::create('foo')->addParameter(PhpParameter::create('bar'));
        $this->assertEquals("/**\n * @param \$bar\n */\npublic function foo(\$bar)\n{\n}\n", $generator->generate($method));

        $method = PhpMethod::create('foo')
            ->addParameter(PhpParameter::create('bar'))
            ->addParameter(PhpParameter::create('baz'))
        ;
        $this->assertEquals(
            "/**\n * @param \$bar\n * @param \$baz\n */\npublic function foo(\$bar, \$baz)\n{\n}\n",
            $generator->generate($method)
        );
    }

    public function testReturnType(): void
    {
        $expected = "/**\n * @return int\n */\npublic function foo(): int\n{\n}\n";
        $generator = new ModelGenerator($this->getConfig());

        $method = PhpMethod::create('foo')->setType('int');
        $this->assertEquals($expected, $generator->generate($method));
    }

    public function testWrongClassThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $generator = $this->getMockBuilder(ModelGenerator::class)->disableOriginalConstructor()->getMock();
        $wrongModel = PhpProperty::create('myMethod');
        $builder = new MethodBuilder($generator);
        $builder->build($wrongModel);
    }
}
