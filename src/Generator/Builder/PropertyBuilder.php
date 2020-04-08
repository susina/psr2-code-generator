<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder;

use Susina\Codegen\Generator\Builder\Parts\ValueBuilderPart;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\PhpProperty;

class PropertyBuilder extends AbstractBuilder
{
    use ValueBuilderPart;

    public function build(AbstractModel $model): void
    {
        if (!$model instanceof PhpProperty) {
            throw new \InvalidArgumentException('The property builder can build property classes only.');
        }

        $this->buildDocblock($model);

        $this->getWriter()->write("{$model->getVisibility()} ");
        $this->getWriter()->write($model->isStatic() ? 'static ' : '');

        if ($this->getConfig()->isPhp74Properties()) {
            $this->getWriter()->write("{$model->getType()} ");
        }

        $this->getWriter()->write('$'.$model->getName());

        if ($model->hasValue()) {
            $this->getWriter()->write(' = ');
            $this->writeValue($model);
        }

        $this->getWriter()->writeln(';');
    }
}
