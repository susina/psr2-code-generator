<?php declare(strict_types=1);

namespace Susina\Codegen\Model;

use Susina\Codegen\Model\Parts\AbstractPart;
use Susina\Codegen\Model\Parts\ConstantsPart;
use Susina\Codegen\Model\Parts\FinalPart;
use Susina\Codegen\Model\Parts\InterfacesPart;
use Susina\Codegen\Model\Parts\PropertiesPart;
use Susina\Codegen\Model\Parts\TraitsPart;

/**
 * Represents a PHP class.
 *
 * @author Thomas Gossmann
 * @author Cristiano Cinotti
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class PhpClass extends AbstractPhpStruct implements GenerateableInterface, TraitsInterface, ConstantsInterface, PropertiesInterface
{
    use AbstractPart;
    use ConstantsPart;
    use FinalPart;
    use InterfacesPart;
    use PropertiesPart;
    use TraitsPart;

    /** @var string */
    private $parentClassName = '';

    /**
     * Creates a new PHP class.
     *
     * @param string $name the qualified name
     */
    final public function __construct(string $name = '')
    {
        parent::__construct($name);
        $this->initProperties();
        $this->initConstants();
        $this->initInterfaces();
        $this->initTraits();
    }

    /**
     * Returns the parent class name.
     *
     * @return string
     */
    public function getParentClassName(): ?string
    {
        return $this->parentClassName;
    }

    /**
     * Sets the parent class name.
     *
     * @param string $name the new parent
     *
     * @return $this
     */
    public function setParentClassName(string $name = ''): self
    {
        $this->parentClassName = $name;

        return $this;
    }

    public function generateDocblock()
    {
        parent::generateDocblock();

        $this->constants->each(function (string $key, PhpConstant $element): void {
            $element->generateDocblock();
        });

        $this->properties->each(function (string $key, PhpProperty $element): void {
            $element->generateDocblock();
        });

        return $this;
    }
}
