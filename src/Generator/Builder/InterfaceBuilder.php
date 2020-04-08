<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder;

use Susina\Codegen\Generator\Builder\Parts\StructBuilderPart;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\PhpInterface;

class InterfaceBuilder extends AbstractBuilder
{
    use StructBuilderPart;

    public function build(AbstractModel $model): void
    {
        if (!$model instanceof PhpInterface) {
            throw new \InvalidArgumentException('Interface builder can build interface classes only');
        }

        $this->sort($model);

        $this->buildHeader($model);

        // signature
        $this->buildSignature($model);

        // body
        $this->getWriter()->writeln("\n{")->indent();
        $this->buildConstants($model);
        $this->buildMethods($model);
        $this->getWriter()->outdent()->rtrim()->write("}\n");
    }

    private function buildSignature(PhpInterface $model): void
    {
        $this->getWriter()->write('interface ');
        $this->getWriter()->write($model->getName());

        if ($model->hasInterfaces()) {
            $this->getWriter()->write(' extends ');
            $this->getWriter()->write(implode(', ', $model->getInterfaces()->toArray()));
        }
    }

    private function sort(PhpInterface $model): void
    {
        $this->sortUseStatements($model);
        $this->sortConstants($model);
        $this->sortMethods($model);
    }
}
