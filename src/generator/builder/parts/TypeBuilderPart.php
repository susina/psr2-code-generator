<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder\parts;

use cristianoc72\codegen\model\AbstractModel;

trait TypeBuilderPart
{
    protected static $noTypeHints = [
        'string', 'int', 'integer', 'bool', 'boolean', 'float', 'double', 'object', 'mixed', 'resource'
    ];
    
    protected static $php7typeHints = [
        'string', 'int', 'integer', 'bool', 'boolean', 'float', 'double'
    ];
    
    protected static $typeHintMap = [
        'string' => 'string',
        'int' => 'int',
        'integer' => 'int',
        'bool' => 'bool',
        'boolean' => 'bool',
        'float' => 'float',
        'double' => 'float'
    ];
    
    /**
     *
     * @param AbstractModel $model
     * @return string|null
     */
    private function getType(AbstractModel $model): ?string
    {
        $type = $model->getType();
        if (!empty($type) && strpos($type, '|') === false
                && (!in_array($type, self::$noTypeHints)
                    || (in_array($type, self::$php7typeHints)))
                ) {
            if (isset(self::$typeHintMap[$type])) {
                return self::$typeHintMap[$type];
            }
            
            return $type;
        }
        
        return null;
    }
}
