<?php declare(strict_types=1);

namespace Susina\Codegen\Model\Parts;

use gossi\docblock\Docblock;

/**
 * Docblock Part.
 *
 * Setting and getting a docblock on a model
 *
 * @author Thomas Gossmann
 */
trait DocblockPart
{
    /** @var Docblock */
    private $docblock;

    /**
     * Sets the docblock.
     *
     * @param Docblock|string $doc
     *
     * @return $this
     */
    public function setDocblock($doc): self
    {
        if (is_string($doc)) {
            $doc = trim($doc);
            $doc = new Docblock($doc);
        }
        $this->docblock = $doc;

        return $this;
    }

    /**
     * Returns the docblock.
     */
    public function getDocblock(): Docblock
    {
        return $this->docblock;
    }
}
