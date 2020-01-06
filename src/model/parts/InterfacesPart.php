<?php declare(strict_types=1);

namespace cristianoc72\codegen\model\parts;

use cristianoc72\codegen\model\PhpInterface;
use phootwork\collection\Set;

/**
 * Interfaces part.
 *
 * For all models that can contain interfaces
 *
 * @author Thomas Gossmann
 */
trait InterfacesPart
{
    /** @var Set */
    private $interfaces;

    /**
     * Adds an interface.
     *
     * If the interface is passed as PhpInterface object,
     * the interface is also added as use statement.
     *
     * @param PhpInterface|string $interface interface or qualified name
     *
     * @return $this
     */
    public function addInterface($interface): self
    {
        if ($interface instanceof PhpInterface) {
            $name = $interface->getName();
            $qname = $interface->getQualifiedName();
            $namespace = $interface->getNamespace();

            if ($namespace != $this->getNamespace()) {
                $this->addUseStatement($qname);
            }
        } else {
            $name = $interface;
        }

        $this->interfaces->add($name);

        return $this;
    }

    /**
     * Returns the interfaces.
     */
    public function getInterfaces(): Set
    {
        return $this->interfaces;
    }

    /**
     * Checks whether interfaces exists.
     *
     * @return bool `true` if interfaces are available and `false` if not
     */
    public function hasInterfaces(): bool
    {
        return !$this->interfaces->isEmpty();
    }

    /**
     * Checks whether an interface exists.
     */
    public function hasInterface(PhpInterface $interface): bool
    {
        return $this->interfaces->contains($interface->getName())
            || $this->interfaces->contains($interface->getQualifiedName());
    }

    /**
     * Checks whether an interface exists.
     *
     * @param string $name interface name
     */
    public function hasInterfaceByName(string $name): bool
    {
        return $this->hasInterface(new PhpInterface($name));
    }

    /**
     * Removes an interface.
     *
     * If the interface is passed as PhpInterface object,
     * the interface is also remove from the use statements.
     *
     * @param PhpInterface $interface interface or qualified name
     *
     * @return $this
     */
    public function removeInterface(PhpInterface $interface): self
    {
        $this->removeUseStatement($interface->getQualifiedName());
        $this->interfaces->remove($interface->getName());

        return $this;
    }

    /**
     * Removes an interface.
     *
     * If the interface is passed as PhpInterface object,
     * the interface is also remove from the use statements.
     *
     * @param string $name interface qualified name
     *
     * @return $this
     */
    public function removeInterfaceByName(string $name): self
    {
        $this->interfaces->remove($name);

        return $this;
    }

    /**
     * Sets a collection of interfaces.
     *
     * @param PhpInterface[] $interfaces
     *
     * @return $this
     */
    public function setInterfaces(array $interfaces): self
    {
        foreach ($interfaces as $interface) {
            $this->addInterface($interface);
        }

        return $this;
    }

    private function initInterfaces(): void
    {
        $this->interfaces = new Set();
    }
}
