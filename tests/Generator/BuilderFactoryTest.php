<?php declare(strict_types=1);

namespace Susina\Codegen\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Susina\Codegen\Generator\Builder\ClassBuilder;
use Susina\Codegen\Generator\BuilderFactory;
use Susina\Codegen\Generator\ModelGenerator;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\PhpClass;

class BuilderFactoryTest extends TestCase
{
    public function testWrongModelClassThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $generator = $this->getMockBuilder(ModelGenerator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $model = new FakeModel();
        $builderFactory = new BuilderFactory($generator);
        $builderFactory->getBuilder($model);
    }

    public function testGetBuilderForSubClass(): void
    {
        $generator = $this->getMockBuilder(ModelGenerator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $model = new Definition();
        $builderFactory = new BuilderFactory($generator);
        $builder = $builderFactory->getBuilder($model);

        $this->assertInstanceOf(ClassBuilder::class, $builder);
    }
}

class FakeModel extends AbstractModel
{
}

class Definition extends PhpClass
{
}
