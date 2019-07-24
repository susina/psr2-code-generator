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

class BuilderFactory
{

    /** @var ModelGenerator */
    private $generator;

    /** @var ClassBuilder  */
    private $classBuilder;

    /** @var ConstantBuilder  */
    private $constantBuilder;

    /** @var FunctionBuilder  */
    private $functionBuilder;

    /** @var InterfaceBuilder  */
    private $interfaceBuilder;

    /** @var MethodBuilder  */
    private $methodBuilder;

    /** @var ParameterBuilder  */
    private $parameterBuilder;

    /** @var PropertyBuilder  */
    private $propertyBuilder;

    /** @var TraitBuilder  */
    private $traitBuilder;
    
    public function __construct(ModelGenerator $generator)
    {
        $this->generator = $generator;
        $this->classBuilder = new ClassBuilder($generator);
        $this->constantBuilder = new ConstantBuilder($generator);
        $this->functionBuilder = new FunctionBuilder($generator);
        $this->interfaceBuilder = new InterfaceBuilder($generator);
        $this->methodBuilder = new MethodBuilder($generator);
        $this->parameterBuilder = new ParameterBuilder($generator);
        $this->propertyBuilder = new PropertyBuilder($generator);
        $this->traitBuilder = new TraitBuilder($generator);
    }
    
    /**
     * Returns the related builder for the given model
     *
     * @param AbstractModel $model
     * @return AbstractBuilder
     */
    public function getBuilder(AbstractModel $model): AbstractBuilder
    {
        switch ($class = get_class($model)) {
            case PhpClass::class:
                $builder = $this->classBuilder;
                break;
            case PhpFunction::class:
                $builder = $this->functionBuilder;
                break;
            case PhpInterface::class:
                $builder = $this->interfaceBuilder;
                break;
            case PhpMethod::class:
                $builder = $this->methodBuilder;
                break;
            case PhpParameter::class:
                $builder = $this->parameterBuilder;
                break;
            case PhpProperty::class:
                $builder = $this->propertyBuilder;
                break;
            case PhpTrait::class:
                $builder = $this->traitBuilder;
                break;
            case PhpConstant::class:
                $builder = $this->constantBuilder;
                break;
            default:
                throw new \InvalidArgumentException(sprintf("No builder for '%s' objects.", $class));
        }

        return $builder;
    }
}
