<?php declare(strict_types=1);

namespace cristianoc72\codegen\model;

use cristianoc72\codegen\model\parts\ConstantsPart;
use cristianoc72\codegen\model\parts\InterfacesPart;

/**
 * Represents a PHP interface.
 *
 * @author Thomas Gossmann
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class PhpInterface extends AbstractPhpStruct implements GenerateableInterface, ConstantsInterface
{
    use ConstantsPart;
    use InterfacesPart;

    /**
     * Create a new PHP interface.
     *
     * @param string $name qualified name
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);
        $this->initConstants();
        $this->initInterfaces();
    }

    /**
     * {@inheritdoc}
     */
    public function generateDocblock(): self
    {
        parent::generateDocblock();

        $this->constants->each(function (string $key, PhpConstant $element): void {
            $element->generateDocblock();
        });

        return $this;
    }
}
