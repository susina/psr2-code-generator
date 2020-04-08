<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder;

use Susina\Codegen\Generator\Builder\Parts\StructBuilderPart;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\PhpTrait;

class TraitBuilder extends AbstractBuilder
{
    use StructBuilderPart;

    public function build(AbstractModel $model): void
    {
        if (!$model instanceof PhpTrait) {
            throw new \InvalidArgumentException('The trait builder can only build Trait classes.');
        }

        $this->sort($model);

        $this->buildHeader($model);

        // signature
        $this->buildSignature($model);

        // body
        $this->getWriter()->writeln("\n{\n")->indent();
        $this->buildTraits($model);
        $this->buildProperties($model);
        $this->buildMethods($model);
        $this->getWriter()->outdent()->rtrim()->write("}\n");
    }

    private function buildSignature(PhpTrait $model): void
    {
        $this->getWriter()->write('trait ');
        $this->getWriter()->write($model->getName());
    }

    private function sort(PhpTrait $model): void
    {
        $this->sortUseStatements($model);
        $this->sortProperties($model);
        $this->sortMethods($model);
    }
}
