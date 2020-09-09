<?php declare(strict_types=1);

namespace Susina\Codegen\Model;

use Susina\Codegen\Model\Parts\ConstantsPart;
use Susina\Codegen\Model\Parts\InterfacesPart;

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
    final public function __construct(string $name = '')
    {
        parent::__construct($name);
        $this->initConstants();
        $this->initInterfaces();
    }

    /**
     * {@inheritdoc}
     */
    public function generateDocblock()
    {
        parent::generateDocblock();

        $this->constants->each(function (string $key, PhpConstant $element): void {
            $element->generateDocblock();
        });

        return $this;
    }
}
