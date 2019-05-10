<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder\parts;

use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\model\RoutineInterface;

trait RoutineBuilderPart
{
    use TypeBuilderPart;
    
    /**
     * @param AbstractModel $model
     * @return void
     */
    abstract protected function generate(AbstractModel $model);

    protected function writeFunctionStatement(RoutineInterface $model): void
    {
        $this->writer->write('function ');
        
        if ($model->isReferenceReturned()) {
            $this->writer->write('& ');
        }
        
        $this->writer->write($model->getName() . '(');
        $this->writeParameters($model);
        $this->writer->write(')');
        $this->writeFunctionReturnType($model);
    }
    
    protected function writeParameters(RoutineInterface $model): void
    {
        $first = true;
        foreach ($model->getParameters() as $parameter) {
            if (!$first) {
                $this->writer->write(', ');
            }
            $first = false;
    
            $this->generate($parameter);
        }
    }
    
    protected function writeFunctionReturnType(RoutineInterface $model): void
    {
        $type = $this->getType($model);
        if ($type !== null) {
            $this->writer->write(': ')->write($type);
        }
    }
    
    protected function writeBody(RoutineInterface $model): void
    {
        $this->writer->writeln("\n{\n")->indent();
        $this->writer->writeln(trim($model->getBody()));
        $this->writer->outdent()->rtrim()->writeln('}');
    }
}
