<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\generator\BuilderFactory;
use cristianoc72\codegen\generator\ModelGenerator;
use cristianoc72\codegen\model\AbstractModel;
use PHPUnit\Framework\TestCase;

class BuilderFactoryTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongModelClassThrowsException()
    {
        $generator = $this->getMockBuilder(ModelGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $model = new FakeModel();
        $builderFactory = new BuilderFactory($generator);
        $builderFactory->getBuilder($model);
    }
}

class FakeModel extends AbstractModel
{
}
