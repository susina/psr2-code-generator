<?php declare(strict_types=1);

namespace Susina\Codegen\Model\Parts;

/**
 * Name part.
 *
 * For all models that do have a name
 *
 * @author Thomas Gossmann
 */
trait NamePart
{
    /** @var string */
    private $name = '';

    /**
     * Sets the name.
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the name.
     */
    public function getName(): string
    {
        return $this->name;
    }
}
