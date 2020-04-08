<?php declare(strict_types=1);

namespace Susina\Codegen\Generator;

use InvalidArgumentException;
use phootwork\collection\Map;
use Susina\Codegen\Generator\Builder\AbstractBuilder;
use Susina\Codegen\Generator\Builder\ClassBuilder;
use Susina\Codegen\Generator\Builder\ConstantBuilder;
use Susina\Codegen\Generator\Builder\InterfaceBuilder;
use Susina\Codegen\Generator\Builder\MethodBuilder;
use Susina\Codegen\Generator\Builder\ParameterBuilder;
use Susina\Codegen\Generator\Builder\PropertyBuilder;
use Susina\Codegen\Generator\Builder\TraitBuilder;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\PhpClass;
use Susina\Codegen\Model\PhpConstant;
use Susina\Codegen\Model\PhpInterface;
use Susina\Codegen\Model\PhpMethod;
use Susina\Codegen\Model\PhpParameter;
use Susina\Codegen\Model\PhpProperty;
use Susina\Codegen\Model\PhpTrait;

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
            PhpInterface::class => new InterfaceBuilder($generator),
            PhpMethod::class => new MethodBuilder($generator),
            PhpParameter::class => new ParameterBuilder($generator),
            PhpProperty::class => new PropertyBuilder($generator),
            PhpTrait::class => new TraitBuilder($generator),
        ]);
    }

    /**
     * Returns the related builder for the given model.
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
