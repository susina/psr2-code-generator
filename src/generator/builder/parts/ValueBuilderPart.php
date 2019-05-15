<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder\parts;

use cristianoc72\codegen\generator\utils\Writer;
use cristianoc72\codegen\model\ValueInterface;
use cristianoc72\codegen\model\PhpConstant;

trait ValueBuilderPart
{
    abstract protected function getWriter(): Writer;

    private function writeValue(ValueInterface $model): void
    {
        if ($model->isExpression()) {
            $this->getWriter()->write($model->getExpression());
        } else {
            $value = $model->getValue();

            if ($value instanceof PhpConstant) {
                $this->getWriter()->write($value->getName());
            } else {
                $this->getWriter()->write($this->exportVar($value));
            }
        }
    }
    
    private function exportVar($value)
    {
        // Simply to be sure a null value is displayed in lowercase.
        if (is_null($value)) {
            return 'null';
        }
        
        return var_export($value, true);
    }
}
