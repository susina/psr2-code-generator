<?php declare(strict_types=1);

namespace cristianoc72\codegen\model\parts;

use cristianoc72\codegen\model\PhpConstant;
use phootwork\collection\Map;
use phootwork\collection\Set;

/**
 * Constants part
 *
 * For all models that can contain constants
 *
 * @author Thomas Gossmann
 */
trait ConstantsPart
{

    /** @var Map */
    private $constants;
    
    private function initConstants()
    {
        $this->constants = new Map();
    }

    /**
     * Sets a collection of constants
     *
     * @param array|PhpConstant[] $constants
     * @return $this
     */
    public function setConstants(array $constants): self
    {
        $normalizedConstants = [];
        foreach ($constants as $name => $value) {
            if ($value instanceof PhpConstant) {
                $name = $value->getName();
            } else {
                $constValue = $value;
                $value = new PhpConstant($name);
                $value->setValue($constValue);
            }

            $normalizedConstants[$name] = $value;
        }

        $this->constants->setAll($normalizedConstants);

        return $this;
    }

    /**
     * Create a PhpConstant instance and adds it to the constants Map
     *
     * @param string $name constant name
     * @param mixed $value
     * @param bool $isExpression
     * @return $this
     */
    public function setConstantByName(string $name, $value = null, bool $isExpression = false): self
    {
        $this->constants->set($name, new PhpConstant($name, $value, $isExpression));

        return $this;
    }

    /**
     * Add a PhpConstant object
     *
     * @param PhpConstant $constant
     * @return $this
     */
    public function setConstant(PhpConstant $constant): self
    {
        $this->constants->set($constant->getName(), $constant);

        return $this;
    }

    /**
     * Adds a PhpConstant object
     *
     * @param PhpConstant $constant
     * @return $this
     */
    public function addConstant(PhpConstant $constant): self
    {
        $this->constants->set($constant->getName(), $constant);

        return $this;
    }

    /**
     * Removes a constant
     *
     * @param PhpConstant $constant
     * @throws \InvalidArgumentException If the constant cannot be found
     * @return $this
     */
    public function removeConstant(PhpConstant $constant): self
    {
        return $this->removeConstantByName($constant->getName());
    }

    /**
     * Removes a constant
     *
     * @param string $name constant name
     * @throws \InvalidArgumentException If the constant cannot be found
     * @return $this
     */
    public function removeConstantByName(string $name): self
    {
        if (!$this->constants->has($name)) {
            throw new \InvalidArgumentException(sprintf('The constant "%s" does not exist.', $name));
        }
        $this->constants->remove($name);

        return $this;
    }

    /**
     * Checks whether a constant exists
     *
     * @param PhpConstant $constant
     * @return bool
     */
    public function hasConstant(PhpConstant $constant): bool
    {
        return $this->constants->has($constant->getName());
    }

    /**
     * Checks whether a constant exists
     *
     * @param string $name constant name
     * @return bool
     */
    public function hasConstantByName(string $name): bool
    {
        return $this->constants->has($name);
    }

    /**
     * Returns a constant.
     *
     * @param string $name constant name
     * @throws \InvalidArgumentException If the constant cannot be found
     * @return PhpConstant
     */
    public function getConstant(string $name): PhpConstant
    {
        if (!$this->constants->has($name)) {
            throw new \InvalidArgumentException(sprintf('The constant "%s" does not exist.', $name));
        }

        return $this->constants->get($name);
    }

    /**
     * Returns all constants
     *
     * @return Map
     */
    public function getConstants(): Map
    {
        return $this->constants;
    }

    /**
     * Returns all constant names
     *
     * @return Set
     */
    public function getConstantNames(): Set
    {
        return $this->constants->keys();
    }

    /**
     * Clears all constants
     *
     * @return $this
     */
    public function clearConstants(): self
    {
        $this->constants->clear();

        return $this;
    }
}
