<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder;

use cristianoc72\codegen\generator\builder\parts\RoutineBuilderPart;
use cristianoc72\codegen\model\AbstractModel;

class FunctionBuilder extends AbstractBuilder
{
    use RoutineBuilderPart;

    public function build(AbstractModel $model): void
    {
        $this->buildDocblock($model);
        
        $this->writeFunctionStatement($model);
        $this->writeBody($model);
    }
}
