<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder;

use Susina\Codegen\Generator\Builder\Parts\StructBuilderPart;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\PhpClass;

class ClassBuilder extends AbstractBuilder
{
    use StructBuilderPart;

    /**
     * {@inheritdoc}
     */
    public function build(AbstractModel $model): void
    {
        if (!$model instanceof PhpClass) {
            throw new \InvalidArgumentException('Class builder can build only class objects.');
        }

        $this->sort($model);

        $this->buildHeader($model);

        // signature
        $this->buildSignature($model);

        // body
        $this->getWriter()->writeln("\n{")->indent();
        $this->buildTraits($model);
        $this->buildConstants($model);
        $this->buildProperties($model);
        $this->buildMethods($model);
        $this->getWriter()->outdent()->rtrim()->write("}\n");
    }

    private function buildSignature(PhpClass $model): void
    {
        if ($model->isAbstract()) {
            $this->getWriter()->write('abstract ');
        }

        if ($model->isFinal()) {
            $this->getWriter()->write('final ');
        }

        $this->getWriter()->write('class ');
        $this->getWriter()->write($model->getName());

        if ($parentClassName = $model->getParentClassName()) {
            $this->getWriter()->write(' extends '.$parentClassName);
        }

        if ($model->hasInterfaces()) {
            $this->getWriter()->write(' implements ');
            $this->getWriter()->write(implode(', ', $model->getInterfaces()->toArray()));
        }
    }

    private function sort(PhpClass $model): void
    {
        $this->sortUseStatements($model);
        $this->sortConstants($model);
        $this->sortProperties($model);
        $this->sortMethods($model);
    }
}
