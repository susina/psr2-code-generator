<?php declare(strict_types=1);

namespace cristianoc72\codegen\model\parts;

/**
 * Type part.
 *
 * For all models that have a type
 *
 * @author Thomas Gossmmann
 */
trait TypePart
{
    /** @var string */
    private $type = '';

    /** @var string */
    private $typeDescription = '';

    /**
     * Sets the type.
     *
     * @return $this
     */
    public function setType(string $type, string $description = '')
    {
        $this->type = $type;
        if ('' !== $description) {
            $this->setTypeDescription($description);
        }

        return $this;
    }

    /**
     * Sets the description for the type.
     *
     * @return $this
     */
    public function setTypeDescription(string $description)
    {
        $this->typeDescription = $description;

        return $this;
    }

    /**
     * Returns the type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns the type description.
     */
    public function getTypeDescription(): string
    {
        return $this->typeDescription;
    }
}
