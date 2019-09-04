<?php declare(strict_types=1);

namespace cristianoc72\codegen\model;

use cristianoc72\codegen\model\parts\DocblockPart;
use cristianoc72\codegen\model\parts\LongDescriptionPart;
use cristianoc72\codegen\model\parts\NamePart;
use cristianoc72\codegen\model\parts\TypeDocblockGeneratorPart;
use cristianoc72\codegen\model\parts\TypePart;
use cristianoc72\codegen\model\parts\ValuePart;
use gossi\docblock\Docblock;
use gossi\docblock\tags\VarTag;

/**
 * Represents a PHP constant.
 *
 * @author Thomas Gossmann
 */
class PhpConstant extends AbstractModel implements GenerateableInterface, DocblockInterface, ValueInterface
{
    use DocblockPart;
    use LongDescriptionPart;
    use NamePart;
    use TypeDocblockGeneratorPart;
    use TypePart;
    use ValuePart;

    /**
     * Creates a new PHP constant.
     *
     * @param string $name
     * @param mixed  $value
     * @param bool   $isExpression
     *
     * @return static
     */
    public static function create(string $name = '', $value = null, bool $isExpression = false)
    {
        return new static($name, $value, $isExpression);
    }

    /**
     * Creates a new PHP constant.
     *
     * @param string $name
     * @param mixed  $value
     * @param bool   $isExpression
     */
    public function __construct(string $name = '', $value = null, bool $isExpression = false)
    {
        $this->setName($name);

        if ($isExpression && is_string($value)) {
            $this->setExpression($value);
        } else {
            $this->setValue($value);
        }
        $this->docblock = new Docblock();
    }

    /**
     * {@inheritdoc}
     */
    public function generateDocblock(): self
    {
        $docblock = $this->getDocblock();
        $docblock->setShortDescription($this->getDescription());
        $docblock->setLongDescription($this->getLongDescription());

        // var tag
        $this->generateTypeTag(new VarTag());

        return $this;
    }
}
