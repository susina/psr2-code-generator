<?php declare(strict_types=1);

namespace cristianoc72\codegen\model;

/**
 * Parent of all models
 *
 * @author Thomas Gossmann
 */
abstract class AbstractModel
{

    /** @var string */
    protected $description = '';

    /**
     * Returns this description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the description, which will also be used when generating a docblock
     *
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets a multi-line description, which will also be used when generating a docblock.
     * Each line is a value of the `$description` array
     *
     * @param array $description
     * @return $this
     */
    public function setMultilineDescription(array $description): self
    {
        $this->description = implode("\n", $description);
        return $this;
    }
}
