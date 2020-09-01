<?php declare(strict_types=1);

namespace Susina\Codegen\Model\Parts;

use gossi\docblock\Docblock;
use gossi\docblock\tags\AbstractTypeTag;
use phootwork\lang\Text;

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
     */
    abstract public function getDocblock(): Docblock;

    /**
     * Returns the type.
     */
    abstract public function getType(): string;

    /**
     * Returns the type description.
     */
    abstract public function getTypeDescription(): string;

    /**
     * Generates a type tag (return or var) but checks if one exists and updates this one.
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
                $ttag->setType($this->transformNullable($this->getType()));
                $ttag->setDescription($this->getTypeDescription());
            }

            // ... anyway create and append
            else {
                $docblock->appendTag(
                    $tag
                        ->setType($this->transformNullable($this->getType()))
                        ->setDescription($this->getTypeDescription())
                );
            }
        }
    }

    /**
     * Transform a `?type` PHP notation in `type|null` docblock notation.
     */
    private function transformNullable(string $type): string
    {
        $text = Text::create($type);

        if ($text->startsWith('?')) {
            $text = $text->trimStart('?')->append('|null');
        }

        return $text->toString();
    }
}
