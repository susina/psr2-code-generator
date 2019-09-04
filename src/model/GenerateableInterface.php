<?php declare(strict_types=1);

namespace cristianoc72\codegen\model;

/**
 * Represents all models that can be generated with a code generator.
 *
 * @author Thomas Gossmann
 */
interface GenerateableInterface
{
    /**
     * Generates docblock based on provided information.
     */
    public function generateDocblock();
}
