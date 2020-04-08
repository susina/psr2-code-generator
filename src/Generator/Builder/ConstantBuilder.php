<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder;

use Susina\Codegen\Generator\Builder\Parts\ValueBuilderPart;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\PhpConstant;

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
