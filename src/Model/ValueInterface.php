<?php declare(strict_types=1);

namespace Susina\Codegen\Model;

interface ValueInterface
{
    /**
     * Sets the value.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value);

    /**
     * Unsets the value.
     *
     * @return $this
     */
    public function unsetValue();

    /**
     * Returns the value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Checks whether a value or expression is set.
     */
    public function hasValue(): bool;

    /**
     * Returns whether an expression is set.
     */
    public function isExpression(): bool;

    /**
     * Sets an expression.
     *
     * @return $this
     */
    public function setExpression(string $expr);

    /**
     * Returns the expression.
     */
    public function getExpression(): string;

    /**
     * Unsets the expression.
     *
     * @return $this
     */
    public function unsetExpression();
}
