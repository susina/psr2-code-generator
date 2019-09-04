<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder;

use cristianoc72\codegen\generator\builder\parts\RoutineBuilderPart;
use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\model\PhpInterface;
use cristianoc72\codegen\model\PhpMethod;

class MethodBuilder extends AbstractBuilder
{
    use RoutineBuilderPart;

    public function build(AbstractModel $model): void
    {
        if (!$model instanceof PhpMethod) {
            throw new \InvalidArgumentException('Method builder can build method classes only.');
        }

        $this->buildDocblock($model);

        if ($model->isFinal()) {
            $this->getWriter()->write('final ');
        }

        if ($model->isAbstract()) {
            $this->getWriter()->write('abstract ');
        }

        $this->getWriter()->write($model->getVisibility().' ');

        if ($model->isStatic()) {
            $this->getWriter()->write('static ');
        }

        $this->writeFunctionStatement($model);

        if ($model->isAbstract() || $model->getParent() instanceof PhpInterface) {
            $this->getWriter()->writeln(';');

            return;
        }

        $this->writeBody($model);
    }
}
