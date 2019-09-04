<?php declare(strict_types=1);

namespace cristianoc72\codegen\model\parts;

use gossi\docblock\Docblock;
use gossi\docblock\tags\AbstractTypeTag;

/**
 * Type docblock generator part.
 *
 * For all models that have a type and need docblock tag generated from it
 *
 * @author Thomas Gossmann
 */
trait TypeDocblockGeneratorPart
{
    /**
     * Returns the docblock.
     *
     * @return Docblock
     */
    abstract public function getDocblock(): Docblock;

    /**
     * Returns the type.
     *
     * @return string
     */
    abstract public function getType(): string;

    /**
     * Returns the type description.
     *
     * @return string
     */
    abstract public function getTypeDescription(): string;

    /**
     * Generates a type tag (return or var) but checks if one exists and updates this one.
     *
     * @param AbstractTypeTag $tag
     */
    protected function generateTypeTag(AbstractTypeTag $tag): void
    {
        $docblock = $this->getDocblock();
        $type = $this->getType();

        if (!empty($type)) {

            // try to find tag at first and update
            $tags = $docblock->getTags($tag->getTagName());
            if ($tags->size() > 0) {
                $ttag = $tags->get(0);
                $ttag->setType($this->getType());
                $ttag->setDescription($this->getTypeDescription());
            }

            // ... anyway create and append
            else {
                $docblock->appendTag(
                    $tag
                    ->setType($this->getType())
                    ->setDescription($this->getTypeDescription())
                );
            }
        }
    }
}
