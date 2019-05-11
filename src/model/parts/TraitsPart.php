<?php declare(strict_types=1);

namespace cristianoc72\codegen\model\parts;

use cristianoc72\codegen\model\PhpTrait;
use phootwork\collection\Set;

/**
 * Traits part
 *
 * For all models that can have traits
 *
 * @author Thomas Gossmann
 */
trait TraitsPart
{

    /** @var Set */
    private $traits;

    private function initTraits()
    {
        $this->traits = new Set();
    }

    /**
     * Adds a use statement with an optional alias
     *
     * @param string $qualifiedName
     * @param null|string $alias
     * @return $this
     */
    abstract public function addUseStatement(string $qualifiedName, ?string $alias = null);

    /**
     * Removes a use statement
     *
     * @param string $qualifiedName
     * @return $this
     */
    abstract public function removeUseStatement(string $qualifiedName);

    /**
     * Returns the namespace
     *
     * @return string
     */
    abstract public function getNamespace(): string;

    /**
     * Adds a trait.
     *
     * If the trait is passed as PhpTrait object,
     * the trait is also added as use statement.
     *
     * @param PhpTrait $trait trait or qualified name
     * @return $this
     */
    public function addTrait(PhpTrait $trait): self
    {
        if ($trait->getNamespace() != $this->getNamespace()) {
            $this->addUseStatement($trait->getQualifiedName());
        }
        $this->traits->add($trait);

        return $this;
    }

    /**
     * Returns a collection of traits
     *
     * @return Set
     */
    public function getTraits(): Set
    {
        return $this->traits;
    }

    /**
     * Checks whether a trait exists
     *
     * @param PhpTrait $trait
     * @return bool `true` if it exists and `false` if not
     */
    public function hasTrait(PhpTrait $trait): bool
    {
        return $this->traits->contains($trait);
    }

    /**
     * Checks whether a trait exists
     *
     * @param string $name
     * @return bool `true` if it exists and `false` if not
     */
    public function hasTraitByName(string $name): bool
    {
        return
            $this->traits->search($name, function (PhpTrait $element, string $query) {
                return $element->getName() === $query;
            });
    }

    /**
     * Removes a trait.
     *
     * If the trait is passed as PhpTrait object,
     * the trait is also removed from use statements.
     *
     * @param PhpTrait $trait trait or qualified name
     * @return $this
     */
    public function removeTrait(PhpTrait $trait): self
    {
        $this->removeUseStatement($trait->getQualifiedName());
        $this->traits->remove($trait);

        return $this;
    }

    /**
     * Removes a trait.
     *
     * If the trait is passed as PhpTrait object,
     * the trait is also removed from use statements.
     *
     * @param string $traitName trait qualified name
     * @return $this
     */
    public function removeTraitByName(string $traitName): self
    {
        $toRemove = $this->traits->find($traitName, function (PhpTrait $element, string $query) {
            return $element->getQualifiedName() === $query;
        });

        return $this->removeTrait($toRemove);
    }

    /**
     * Sets a collection of traits
     *
     * @param PhpTrait[] $traits
     * @return $this
     * @throw \InvalidArgumentException if wrong type given
     */
    public function setTraits(array $traits)
    {
        $this->traits->clear();
        foreach ($traits as $trait) {
            if (is_string($trait)) {
                $trait = new PhpTrait($trait);
            }

            if ($trait instanceof PhpTrait) {
                $this->traits->add($trait);
                continue;
            }

            throw new \InvalidArgumentException('`setTrait` function expects an array of `PhpTrait` instance: '
                . gettype($trait) . ' given.');
        }

        return $this;
    }
}
