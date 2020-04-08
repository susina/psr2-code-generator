<?php declare(strict_types=1);

namespace Susina\Codegen\Model;

/**
 * Represents all models that can be generated with a code generator.
 *
 * @author Thomas Gossmann
 */
interface GenerateableInterface
{
    /**
     * Generates docblock based on provided information.
     *
     * @psalm-suppress MissingReturnType
     */
    public function generateDocblock();
}
