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

    private function initTraits(): void
    {
        $this->traits = new Set();
    }

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
     *
     * @psalm-suppress TooManyArguments
     */
    public function hasTraitByName(string $name): bool
    {
        return
            $this->traits->search($name, function (PhpTrait $element, string $query): bool {
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
     *
     * @psalm-suppress TooManyArguments
     */
    public function removeTraitByName(string $traitName): self
    {
        $toRemove = $this->traits->find($traitName, function(PhpTrait $element, string $query): bool {
            return $element->getQualifiedName() === $query;
        });

        return $this->removeTrait($toRemove);
    }

    /**
     * Sets a collection of traits
     *
     * @param array $traits
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
