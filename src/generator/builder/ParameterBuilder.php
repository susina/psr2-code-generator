<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder;

use cristianoc72\codegen\generator\builder\parts\TypeBuilderPart;
use cristianoc72\codegen\generator\builder\parts\ValueBuilderPart;
use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\model\PhpParameter;

class ParameterBuilder extends AbstractBuilder
{
    use ValueBuilderPart;
    use TypeBuilderPart;

    public function build(AbstractModel $model): void
    {
        if (! $model instanceof PhpParameter) {
            throw new \InvalidArgumentException('Parameter builder can build parameter classes only.');
        }

        $type = $this->getType($model);
        if ($type !== null) {
            $this->getWriter()->write($type . ' ');
        }
    
        if ($model->isPassedByReference()) {
            $this->getWriter()->write('&');
        }
    
        $this->getWriter()->write('$' . $model->getName());
    
        if ($model->hasValue()) {
            $this->getWriter()->write(' = ');
    
            $this->writeValue($model);
        }
    }
}
