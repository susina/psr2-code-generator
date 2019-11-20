<?php declare(strict_types=1);

namespace cristianoc72\codegen\model;

use cristianoc72\codegen\model\parts\AbstractPart;
use cristianoc72\codegen\model\parts\ConstantsPart;
use cristianoc72\codegen\model\parts\FinalPart;
use cristianoc72\codegen\model\parts\InterfacesPart;
use cristianoc72\codegen\model\parts\PropertiesPart;
use cristianoc72\codegen\model\parts\TraitsPart;

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
    public function __construct(?string $name = null)
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
     * @param null|string $name the new parent
     *
     * @return $this
     */
    public function setParentClassName(?string $name): self
    {
        $this->parentClassName = $name ?? '';

        return $this;
    }

    public function generateDocblock(): self
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
