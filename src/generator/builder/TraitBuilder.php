<?php
namespace cristianoc72\codegen\generator\builder;

use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\generator\builder\parts\StructBuilderPart;
use cristianoc72\codegen\model\PhpTrait;

class TraitBuilder extends AbstractBuilder
{
    use StructBuilderPart;
    
    public function build(AbstractModel $model)
    {
        $this->sort($model);
    
        $this->buildHeader($model);
    
        // signature
        $this->buildSignature($model);
    
        // body
        $this->writer->writeln("\n{\n")->indent();
        $this->buildTraits($model);
        $this->buildProperties($model);
        $this->buildMethods($model);
        $this->writer->outdent()->rtrim()->write("}\n");
    }
    
    private function buildSignature(PhpTrait $model)
    {
        $this->writer->write('trait ');
        $this->writer->write($model->getName());
    }
    
    private function sort(PhpTrait $model)
    {
        $this->sortUseStatements($model);
        $this->sortProperties($model);
        $this->sortMethods($model);
    }
}
