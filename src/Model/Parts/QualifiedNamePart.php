<?php declare(strict_types=1);

namespace Susina\Codegen\Model\Parts;

/**
 * Qualified name part.
 *
 * For all models that have a name and namespace
 *
 * @author Thomas Gossmann
 */
trait QualifiedNamePart
{
    use NamePart;

    /** @var string */
    private $namespace = '';

    /**
     * Sets the namespace.
     *
     * @return $this
     */
    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * In contrast to setName(), this method accepts the fully qualified name
     * including the namespace.
     *
     * @return $this
     */
    public function setQualifiedName(string $name): self
    {
        if (false !== $pos = strrpos($name, '\\')) {
            $this->namespace = trim(substr($name, 0, $pos), '\\');
            $this->name = substr($name, $pos + 1);

            return $this;
        }

        $this->namespace = '';
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the namespace.
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Returns the qualified name.
     */
    public function getQualifiedName(): string
    {
        if ($this->namespace) {
            return $this->namespace.'\\'.$this->name;
        }

        return $this->name;
    }
}
