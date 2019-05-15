<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder;

use cristianoc72\codegen\generator\builder\parts\RoutineBuilderPart;
use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\model\PhpFunction;

class FunctionBuilder extends AbstractBuilder
{
    use RoutineBuilderPart;

    public function build(AbstractModel $model): void
    {
        if (! $model instanceof PhpFunction) {
            throw new \InvalidArgumentException('Function builder can build function classes only.');
        }

        $this->buildDocblock($model);
        
        $this->writeFunctionStatement($model);
        $this->writeBody($model);
    }
}
