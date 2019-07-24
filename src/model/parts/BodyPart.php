<?php declare(strict_types=1);

namespace cristianoc72\codegen\model\parts;

/**
 * Body Part
 *
 * For all models that do have a code body
 *
 * @author Thomas Gossmann
 */
trait BodyPart
{

    /** @var string */
    private $body = '';

    /**
     * Sets the body for this
     *
     * @param string $body
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Returns the body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Append a string to the body of this
     *
     * @param string $toAppend
     *
     * @return $this
     */
    public function appendToBody(string $toAppend): self
    {
        $this->body .= $toAppend;

        return $this;
    }
}
