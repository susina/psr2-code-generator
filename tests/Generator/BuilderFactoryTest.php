<?php declare(strict_types=1);

namespace Susina\Codegen\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Susina\Codegen\Generator\BuilderFactory;
use Susina\Codegen\Generator\ModelGenerator;
use Susina\Codegen\Model\AbstractModel;

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
}

class FakeModel extends AbstractModel
{
}
