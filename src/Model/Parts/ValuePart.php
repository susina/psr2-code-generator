<?php declare(strict_types=1);

namespace Susina\Codegen\Model\Parts;

use Susina\Codegen\Model\PhpConstant;

/**
 * Value part.
 *
 * For all models that have a value (or expression)
 *
 * @author Thomas Gossmann
 */
trait ValuePart
{
    /** @var mixed */
    private $value;

    /** @var bool */
    private $hasValue = false;

    /** @var string */
    private $expression = '';

    /** @var bool */
    private $hasExpression = false;

    /**
     * Sets the value.
     *
     * @param mixed $value
     *
     * @throws \InvalidArgumentException if the value is not an accepted primitve
     *
     * @return $this
     */
    public function setValue($value): self
    {
        if (!$this->isPrimitive($value)) {
            throw new \InvalidArgumentException('Use setValue() only for primitives and PhpConstant, anyway use setExpression() instead.');
        }
        $this->value = $value;
        $this->hasValue = true;

        return $this;
    }

    /**
     * Unsets the value.
     *
     * @return $this
     */
    public function unsetValue(): self
    {
        $this->value = null;
        $this->hasValue = false;

        return $this;
    }

    /**
     * Returns the value.
     *
     * @return null|bool|float|int|PhpConstant|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Checks whether a value or expression is set.
     */
    public function hasValue(): bool
    {
        return $this->hasValue || $this->hasExpression;
    }

    /**
     * Returns whether an expression is set.
     */
    public function isExpression(): bool
    {
        return $this->hasExpression;
    }

    /**
     * Sets an expression.
     *
     * @return $this
     */
    public function setExpression(string $expr): self
    {
        $this->expression = $expr;
        $this->hasExpression = true;

        return $this;
    }

    /**
     * Returns the expression.
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * Unsets the expression.
     *
     * @return $this
     */
    public function unsetExpression(): self
    {
        $this->expression = '';
        $this->hasExpression = false;

        return $this;
    }

    /**
     * Returns whether the given value is a primitive.
     *
     * @param mixed $value
     */
    private function isPrimitive($value): bool
    {
        return is_string($value)
            || is_int($value)
            || is_float($value)
            || is_bool($value)
            || is_null($value)
            || ($value instanceof PhpConstant);
    }
}
