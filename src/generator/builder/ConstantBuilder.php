<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder;

use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\generator\builder\parts\ValueBuilderPart;

class ConstantBuilder extends AbstractBuilder
{
    use ValueBuilderPart;
    
    public function build(AbstractModel $model): void
    {
        $this->buildDocblock($model);
        $this->writer->write('const ' . $model->getName() . ' = ');
        $this->writeValue($model);
        $this->writer->writeln(';');
    }
}
