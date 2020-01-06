<?php declare(strict_types=1);

namespace cristianoc72\codegen\model\parts;

/**
 * Reference return part.
 *
 * Keeps track whether the return value is a reference or not
 *
 * @author Thomas Gossmann
 */
trait ReferenceReturnPart
{
    /** @var bool */
    private $referenceReturned = false;

    /**
     * Set true if a reference is returned of false if not.
     *
     * @return $this
     */
    public function setReferenceReturned(bool $bool): self
    {
        $this->referenceReturned = (bool) $bool;

        return $this;
    }

    /**
     * Returns whether a reference is returned.
     */
    public function isReferenceReturned(): bool
    {
        return $this->referenceReturned;
    }
}
