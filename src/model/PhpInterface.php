<?php declare(strict_types=1);

namespace cristianoc72\codegen\model;

use cristianoc72\codegen\model\parts\ConstantsPart;
use cristianoc72\codegen\model\parts\InterfacesPart;

/**
 * Represents a PHP interface.
 *
 * @author Thomas Gossmann
 */
class PhpInterface extends AbstractPhpStruct implements GenerateableInterface, ConstantsInterface
{
    use ConstantsPart;
    use InterfacesPart;

    /**
     * Create a new PHP interface
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
     * @inheritDoc
     */
    public function generateDocblock()
    {
        parent::generateDocblock();

        foreach ($this->constants as $constant) {
            $constant->generateDocblock();
        }
    }
}
