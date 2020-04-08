<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder\Parts;

use Susina\Codegen\Generator\Utils\Writer;
use Susina\Codegen\Model\PhpConstant;
use Susina\Codegen\Model\ValueInterface;

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

    /**
     * @param mixed $value
     *
     * @return mixed|string
     */
    private function exportVar($value)
    {
        // Simply to be sure a null value is displayed in lowercase.
        if (is_null($value)) {
            return 'null';
        }

        return var_export($value, true);
    }
}
