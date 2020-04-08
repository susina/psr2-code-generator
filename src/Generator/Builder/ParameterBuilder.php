<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder;

use Susina\Codegen\Generator\Builder\Parts\TypeBuilderPart;
use Susina\Codegen\Generator\Builder\Parts\ValueBuilderPart;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\PhpParameter;

class ParameterBuilder extends AbstractBuilder
{
    use ValueBuilderPart;
    use TypeBuilderPart;

    public function build(AbstractModel $model): void
    {
        if (!$model instanceof PhpParameter) {
            throw new \InvalidArgumentException('Parameter builder can build parameter classes only.');
        }

        $type = $this->getType($model);
        if (null !== $type) {
            $this->getWriter()->write($type.' ');
        }

        if ($model->isPassedByReference()) {
            $this->getWriter()->write('&');
        }

        $this->getWriter()->write('$'.$model->getName());

        if ($model->hasValue()) {
            $this->getWriter()->write(' = ');

            $this->writeValue($model);
        }
    }
}
