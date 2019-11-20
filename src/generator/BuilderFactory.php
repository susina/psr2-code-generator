<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator;

use cristianoc72\codegen\generator\builder\AbstractBuilder;
use cristianoc72\codegen\generator\builder\ClassBuilder;
use cristianoc72\codegen\generator\builder\ConstantBuilder;
use cristianoc72\codegen\generator\builder\FunctionBuilder;
use cristianoc72\codegen\generator\builder\InterfaceBuilder;
use cristianoc72\codegen\generator\builder\MethodBuilder;
use cristianoc72\codegen\generator\builder\ParameterBuilder;
use cristianoc72\codegen\generator\builder\PropertyBuilder;
use cristianoc72\codegen\generator\builder\TraitBuilder;
use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\model\PhpClass;
use cristianoc72\codegen\model\PhpConstant;
use cristianoc72\codegen\model\PhpFunction;
use cristianoc72\codegen\model\PhpInterface;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpParameter;
use cristianoc72\codegen\model\PhpProperty;
use cristianoc72\codegen\model\PhpTrait;
use InvalidArgumentException;
use phootwork\collection\Map;

class BuilderFactory
{
    /** @var ModelGenerator */
    private $generator;

    /** @var Map */
    private $builders;

    public function __construct(ModelGenerator $generator)
    {
        $this->generator = $generator;
        $this->builders = new Map();
        $this->builders->setAll([
            PhpClass::class => new ClassBuilder($generator),
            PhpConstant::class => new ConstantBuilder($generator),
            PhpFunction::class => new FunctionBuilder($generator),
            PhpInterface::class => new InterfaceBuilder($generator),
            PhpMethod::class => new MethodBuilder($generator),
            PhpParameter::class => new ParameterBuilder($generator),
            PhpProperty::class => new PropertyBuilder($generator),
            PhpTrait::class => new TraitBuilder($generator),
        ]);
    }

    /**
     * Returns the related builder for the given model.
     *
     * @param AbstractModel $model
     *
     * @return AbstractBuilder
     */
    public function getBuilder(AbstractModel $model): AbstractBuilder
    {
        $modelClass = get_class($model);

        if (!$this->builders->has($modelClass)) {
            throw new InvalidArgumentException(sprintf("No builder for '%s' objects.", get_class($model)));
        }

        return $this->builders->get($modelClass);
    }
}
