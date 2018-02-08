<?php
namespace cristianoc72\codegen\generator\builder;

use cristianoc72\codegen\generator\builder\parts\StructBuilderPart;
use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\model\PhpInterface;

class InterfaceBuilder extends AbstractBuilder
{
    use StructBuilderPart;
    
    public function build(AbstractModel $model)
    {
        $this->sort($model);
    
        $this->buildHeader($model);
        
        // signature
        $this->buildSignature($model);
    
        // body
        $this->writer->writeln("\n{")->indent();
        $this->buildConstants($model);
        $this->buildMethods($model);
        $this->writer->outdent()->rtrim()->write("}\n");
    }
    
    private function buildSignature(PhpInterface $model)
    {
        $this->writer->write('interface ');
        $this->writer->write($model->getName());
        
        if ($model->hasInterfaces()) {
            $this->writer->write(' extends ');
            $this->writer->write(implode(', ', $model->getInterfaces()->toArray()));
        }
    }
    
    private function sort(PhpInterface $model)
    {
        $this->sortUseStatements($model);
        $this->sortConstants($model);
        $this->sortMethods($model);
    }
}