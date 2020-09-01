<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder\Parts;

use phootwork\lang\Text;
use Susina\Codegen\Model\AbstractModel;

trait TypeBuilderPart
{
    /** @var string[] */
    protected static $noTypeHints = [
        'string', 'int', 'integer', 'bool', 'boolean', 'float', 'double', 'object', 'mixed', 'resource', '$this',
    ];

    /** @var string[] */
    protected static $php7typeHints = [
        'string', 'int', 'integer', 'bool', 'boolean', 'float', 'double',
    ];

    /** @var array */
    protected static $typeHintMap = [
        'string' => 'string',
        'int' => 'int',
        'integer' => 'int',
        'bool' => 'bool',
        'boolean' => 'bool',
        'float' => 'float',
        'double' => 'float',
    ];

    /**
     * @psalm-suppress UndefinedMethod
     * Concrete model classes using this trait always have `getType()` method.
     */
    private function getType(AbstractModel $model): ?string
    {
        $type = $this->removeThis($model->getType());
        $type = $this->checkNullable($type);

        if (!empty($type) && false === strpos($type, '|')
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

    /**
     * Remove `$this` key, from the list of the return types. I.e:.
     *
     * @see Susina\Tests\Generator\MethodGeneratorTest for examples
     */
    private function removeThis(string $type): string
    {
        $text = Text::create($type);
        if ($text->contains('|')) {
            $parts = $text->split('|');
            if ($parts->contains('$this')) {
                $parts->remove('$this');
            }
            $text = $parts->join('|');
        }

        return $text->toString();
    }

    /**
     * Manage nullable types.
     */
    private function checkNullable(string $type): string
    {
        $text = Text::create($type);
        if ($text->contains('|') && $text->toLowerCase()->contains('null')) {
            $parts = $text->split('|');
            if ($parts->size() > 2) {
                //mixed type that can be return also null
                return $text->toString();
            }
            $parts->remove('null', 'NULL', 'Null');
            $text = $parts->join('|')->prepend('?');
        }

        return $text->toString();
    }
}
