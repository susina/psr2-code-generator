<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder;

use cristianoc72\codegen\generator\builder\parts\ValueBuilderPart;
use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\model\PhpConstant;

class ConstantBuilder extends AbstractBuilder
{
    use ValueBuilderPart;

    public function build(AbstractModel $model): void
    {
        if (!$model instanceof PhpConstant) {
            throw new \InvalidArgumentException('Constant builder can build costant classes only.');
        }

        $this->buildDocblock($model);
        $this->getWriter()->write('const '.$model->getName().' = ');
        $this->writeValue($model);
        $this->getWriter()->writeln(';');
    }
}
