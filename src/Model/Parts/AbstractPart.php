<?php declare(strict_types=1);

namespace Susina\Codegen\Model\Parts;

/**
 * Abstract Part.
 *
 * Keeps track if the model has an abstract modifier or not
 *
 * @author Thomas Gossmann
 */
trait AbstractPart
{
    /** @var bool */
    private $abstract = false;

    /**
     * Returns whether this is abstract.
     *
     * @return bool `true` for abstract and `false` if not
     */
    public function isAbstract(): bool
    {
        return $this->abstract;
    }

    /**
     * Sets this to abstract.
     *
     * @param bool $bool `true` for abstract and `false` if not
     *
     * @return $this
     */
    public function setAbstract(bool $bool): self
    {
        $this->abstract = (bool) $bool;

        return $this;
    }
}
