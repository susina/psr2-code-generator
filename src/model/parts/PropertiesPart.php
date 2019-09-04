<?php declare(strict_types=1);

namespace cristianoc72\codegen\model\parts;

use cristianoc72\codegen\model\PhpProperty;
use phootwork\collection\Map;
use phootwork\collection\Set;

/**
 * Properties part.
 *
 * For all models that can have properties
 *
 * @author Thomas Gossmann
 */
trait PropertiesPart
{
    /** @var Map */
    private $properties;

    private function initProperties(): void
    {
        $this->properties = new Map();
    }

    /**
     * Sets a collection of properties.
     *
     * @param PhpProperty[] $properties
     *
     * @return $this
     */
    public function setProperties(array $properties): self
    {
        $this->clearProperties();
        foreach ($properties as $property) {
            $this->setProperty($property);
        }

        return $this;
    }

    /**
     * Adds a property.
     *
     * @param PhpProperty $property
     *
     * @return $this
     */
    public function setProperty(PhpProperty $property): self
    {
        $property->setParent($this);
        $this->properties->set($property->getName(), $property);

        return $this;
    }

    /**
     * Removes a property.
     *
     * @param PhpProperty $property
     *
     * @throws \InvalidArgumentException If the property cannot be found
     *
     * @return $this
     */
    public function removeProperty(PhpProperty $property): self
    {
        return $this->removePropertyByName($property->getName());
    }

    /**
     * Removes a property.
     *
     * @param string $name property name
     *
     * @throws \InvalidArgumentException If the property cannot be found
     *
     * @return $this
     */
    public function removePropertyByName(string $name): self
    {
        if (!$this->properties->has($name)) {
            throw new \InvalidArgumentException(sprintf('The property "%s" does not exist.', $name));
        }
        $p = $this->properties->get($name);
        $p->setParent(null);
        $this->properties->remove($name);

        return $this;
    }

    /**
     * Checks whether a property exists.
     *
     * @param PhpProperty $property
     *
     * @return bool `true` if a property exists and `false` if not
     */
    public function hasProperty(PhpProperty $property): bool
    {
        return $this->hasPropertyByName($property->getName());
    }

    /**
     * Checks whether a property exists.
     *
     * @param string $name property name
     *
     * @return bool `true` if a property exists and `false` if not
     */
    public function hasPropertyByName(string $name): bool
    {
        return $this->properties->has($name);
    }

    /**
     * Returns a property.
     *
     * @param string $name property name
     *
     * @throws \InvalidArgumentException If the property cannot be found
     *
     * @return PhpProperty
     */
    public function getProperty(string $name): PhpProperty
    {
        if (!$this->properties->has($name)) {
            throw new \InvalidArgumentException(sprintf('The property "%s" does not exist.', $name));
        }

        return $this->properties->get($name);
    }

    /**
     * Returns a collection of properties.
     *
     * @return Map
     */
    public function getProperties(): Map
    {
        return $this->properties;
    }

    /**
     * Returns all property names.
     *
     * @return Set
     */
    public function getPropertyNames(): Set
    {
        return $this->properties->keys();
    }

    /**
     * Clears all properties.
     *
     * @return $this
     */
    public function clearProperties(): self
    {
        $this->properties->each(function (string $key, PhpProperty $element): void {
            $element->setParent(null);
        });
        $this->properties->clear();

        return $this;
    }
}
