<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder\Parts;

use Susina\Codegen\Generator\Utils\Writer;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\RoutineInterface;

trait RoutineBuilderPart
{
    use TypeBuilderPart;

    abstract protected function generate(AbstractModel $model);

    abstract protected function getWriter(): Writer;

    protected function writeFunctionStatement(RoutineInterface $model): void
    {
        $this->getWriter()->write('function ');

        if ($model->isReferenceReturned()) {
            $this->getWriter()->write('& ');
        }

        $this->getWriter()->write($model->getName().'(');
        $this->writeParameters($model);
        $this->getWriter()->write(')');
        $this->writeFunctionReturnType($model);
    }

    protected function writeParameters(RoutineInterface $model): void
    {
        $first = true;
        foreach ($model->getParameters() as $parameter) {
            if (!$first) {
                $this->getWriter()->write(', ');
            }
            $first = false;

            $this->generate($parameter);
        }
    }

    /**
     * @psalm-suppress InvalidArgument
     * $model is always and AbstractModel subclass
     */
    protected function writeFunctionReturnType(RoutineInterface $model): void
    {
        $type = $this->getType($model);
        if (null !== $type) {
            $this->getWriter()->write(': ')->write($type);
        }
    }

    protected function writeBody(RoutineInterface $model): void
    {
        $this->getWriter()->writeln("\n{\n")->indent();
        $this->getWriter()->writeln(trim($model->getBody()));
        $this->getWriter()->outdent()->rtrim()->writeln('}');
    }
}
