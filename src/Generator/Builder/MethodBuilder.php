<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder;

use phootwork\lang\Text;
use Susina\Codegen\Generator\Builder\Parts\RoutineBuilderPart;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\PhpClass;
use Susina\Codegen\Model\PhpInterface;
use Susina\Codegen\Model\PhpMethod;

class MethodBuilder extends AbstractBuilder
{
    use RoutineBuilderPart;

    public function build(AbstractModel $model): void
    {
        if (!$model instanceof PhpMethod) {
            throw new \InvalidArgumentException('Method builder can build method classes only.');
        }

        $this->discoverThis($model);

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

    /**
     * Discover the class relative to the `$this` notation.
     */
    private function discoverThis(PhpMethod $method): void
    {
        $text = new Text($method->getType());
        if ($text->equals('$this')) {
            if ($method->getParent() instanceof PhpClass) {
                $text = $text->append('|')->append($method->getParent()->getName());
            }
        }

        $method->setType($text->toString());
    }
}
