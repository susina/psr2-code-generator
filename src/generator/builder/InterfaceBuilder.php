<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder;

use cristianoc72\codegen\generator\builder\parts\StructBuilderPart;
use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\model\PhpInterface;

class InterfaceBuilder extends AbstractBuilder
{
    use StructBuilderPart;
    
    public function build(AbstractModel $model): void
    {
        if (! $model instanceof PhpInterface) {
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
