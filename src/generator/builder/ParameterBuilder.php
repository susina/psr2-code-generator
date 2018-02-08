<?php
namespace cristianoc72\codegen\generator\builder;

use cristianoc72\codegen\generator\builder\parts\TypeBuilderPart;
use cristianoc72\codegen\generator\builder\parts\ValueBuilderPart;
use cristianoc72\codegen\model\AbstractModel;

class ParameterBuilder extends AbstractBuilder
{
    use ValueBuilderPart;
    use TypeBuilderPart;

    public function build(AbstractModel $model)
    {
        $type = $this->getType($model, $this->config->getGenerateScalarTypeHints());
        if ($type !== null) {
            $this->writer->write($type . ' ');
        }
    
        if ($model->isPassedByReference()) {
            $this->writer->write('&');
        }
    
        $this->writer->write('$' . $model->getName());
    
        if ($model->hasValue()) {
            $this->writer->write(' = ');
    
            $this->writeValue($model);
        }
    }
}
