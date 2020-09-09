<?php declare(strict_types=1);

namespace Susina\Codegen\Model;

use gossi\docblock\Docblock;
use gossi\docblock\tags\VarTag;
use Susina\Codegen\Model\Parts\DocblockPart;
use Susina\Codegen\Model\Parts\LongDescriptionPart;
use Susina\Codegen\Model\Parts\NamePart;
use Susina\Codegen\Model\Parts\TypeDocblockGeneratorPart;
use Susina\Codegen\Model\Parts\TypePart;
use Susina\Codegen\Model\Parts\ValuePart;

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
     * @param mixed $value
     */
    final public function __construct(string $name = '', $value = null, bool $isExpression = false)
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
     * Creates a new PHP constant.
     *
     * @param mixed $value
     *
     * @return static
     */
    public static function create(string $name = '', $value = null, bool $isExpression = false)
    {
        return new static($name, $value, $isExpression);
    }

    /**
     * {@inheritdoc}
     */
    public function generateDocblock()
    {
        $docblock = $this->getDocblock();
        $docblock->setShortDescription($this->getDescription());
        $docblock->setLongDescription($this->getLongDescription());

        // var tag
        $this->generateTypeTag(new VarTag());

        return $this;
    }
}
