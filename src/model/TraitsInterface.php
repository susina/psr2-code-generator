<?php declare(strict_types=1);

namespace cristianoc72\codegen\model;

use phootwork\collection\Set;

/**
 * Represents models that can have traits.
 *
 * @author Thomas Gossmann
 */
interface TraitsInterface
{
    /**
     * Adds a trait.
     *
     * If the trait is passed as PhpTrait object,
     * the trait is also added as use statement.
     *
     * @param PhpTrait $trait
     *
     * @return $this
     */
    public function addTrait(PhpTrait $trait);

    /**
     * Returns a collection of traits.
     *
     * @return Set
     */
    public function getTraits(): Set;

    /**
     * Checks whether a trait exists.
     *
     * @param PhpTrait $trait
     *
     * @return bool `true` if it exists and `false` if not
     */
    public function hasTrait(PhpTrait $trait): bool;

    /**
     * Checks whether a trait exists.
     *
     * @param string $traitName The name of the trait
     *
     * @return bool `true` if it exists and `false` if not
     */
    public function hasTraitByName(string $traitName): bool;

    /**
     * Removes a trait.
     *
     * If the trait is passed as PhpTrait object,
     * the trait is also removed from use statements.
     *
     * @param PhpTrait $trait
     *
     * @return $this
     */
    public function removeTrait(PhpTrait $trait);

    /**
     * Removes a trait.
     *
     * If the trait is passed as PhpTrait object,
     * the trait is also removed from use statements.
     *
     * @param string $traitName trait qualified name
     *
     * @return $this
     */
    public function removeTraitByName(string $traitName);

    /**
     * Sets a collection of traits.
     *
     * @param PhpTrait[] $traits
     *
     * @return $this
     */
    public function setTraits(array $traits);
}
