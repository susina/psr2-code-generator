<?php declare(strict_types=1);

namespace Susina\Codegen\Model;

use Susina\Codegen\Model\Parts\PropertiesPart;
use Susina\Codegen\Model\Parts\TraitsPart;

/**
 * Represents a PHP trait.
 *
 * @author Thomas Gossmann
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class PhpTrait extends AbstractPhpStruct implements GenerateableInterface, TraitsInterface, PropertiesInterface
{
    use PropertiesPart;
    use TraitsPart;

    /**
     * Creates a new PHP trait.
     *
     * @param string $name qualified name
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    final public function __construct(string $name = '')
    {
        parent::__construct($name);
        $this->initProperties();
        $this->initTraits();
    }

    /**
     * {@inheritdoc}
     */
    public function generateDocblock()
    {
        parent::generateDocblock();

        $this->properties->each(function (string $key, PhpProperty $element): void {
            $element->generateDocblock();
        });

        return $this;
    }
}
