<?php declare(strict_types=1);
/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace cristianoc72\codegen\model;

use cristianoc72\codegen\model\parts\DocblockPart;
use cristianoc72\codegen\model\parts\LongDescriptionPart;
use cristianoc72\codegen\model\parts\QualifiedNamePart;
use gossi\docblock\Docblock;
use phootwork\collection\Map;
use phootwork\collection\Set;

/**
 * Represents an abstract php structure (class, trait or interface).
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Thomas Gossmann
 */
abstract class AbstractPhpStruct extends AbstractModel implements NamespaceInterface, DocblockInterface
{
    use DocblockPart;
    use LongDescriptionPart;
    use QualifiedNamePart;

    /** @var Map */
    private $useStatements;

    /** @var Set */
    private $requiredFiles;

    /** @var Map */
    private $methods;

    /**
     * Creates a new struct
     *
     * @param string $name the fqcn
     * @return static
     */
    public static function create(string $name = null)
    {
        return new static($name);
    }

    /**
     * Creates a new struct
     *
     * @param string $name the fqcn
     */
    public function __construct(?string $name = null)
    {
        $this->setQualifiedName($name ?? '');
        $this->docblock = new Docblock();
        $this->useStatements = new Map();
        $this->requiredFiles = new Set();
        $this->methods = new Map();
    }

    /**
     * Sets requried files
     *
     * @param array $files
     * @return $this
     */
    public function setRequiredFiles(array $files): self
    {
        $this->requiredFiles = new Set($files);

        return $this;
    }

    /**
     * Adds a new required file
     *
     * @param string $file
     * @return $this
     */
    public function addRequiredFile(string $file): self
    {
        $this->requiredFiles->add($file);

        return $this;
    }

    /**
     * Returns required files
     *
     * @return Set collection of filenames
     */
    public function getRequiredFiles(): Set
    {
        return $this->requiredFiles;
    }

    /**
     * Sets use statements
     *
     * @see #addUseStatement
     * @see #declareUses()
     * @param array $useStatements
     * @return $this
     */
    public function setUseStatements(array $useStatements): self
    {
        $this->useStatements->clear();
        foreach ($useStatements as $alias => $useStatement) {
            $this->addUseStatement($useStatement, $alias);
        }

        return $this;
    }

    /**
     * Adds a use statement with an optional alias
     *
     * @param string $qualifiedName
     * @param null|string $alias
     * @return $this
     */
    public function addUseStatement(string $qualifiedName, ?string $alias = null): self
    {
        if (!is_string($alias)) {
            if (false !== $pos = strrpos($qualifiedName, '\\')) {
                $alias = substr($qualifiedName, $pos + 1);
            } else {
                $alias = $qualifiedName;
            }
        }

        $this->useStatements->set($alias, $qualifiedName);

        return $this;
    }
    
    /**
     * Clears all use statements
     *
     * @return $this
     */
    public function clearUseStatements(): self
    {
        $this->useStatements->clear();
        
        return $this;
    }

    /**
     * Declares multiple use statements at once.
     *
     * @param ... use statements multiple qualified names
     * @return $this
     */
    public function declareUses(): self
    {
        foreach (func_get_args() as $name) {
            $this->declareUse($name);
        }
        return $this;
    }

    /**
     * Declares a "use $fullClassName" with " as $alias" if $alias is available,
     * and returns its alias (or not qualified classname) to be used in your actual
     * php code.
     *
     * If the class has already been declared you get only the set alias.
     *
     * @param string $qualifiedName
     * @param null|string $alias
     * @return string the used alias
     */
    public function declareUse(string $qualifiedName, ?string $alias = null)
    {
        $qualifiedName = trim($qualifiedName, '\\');
        if (!$this->hasUseStatement($qualifiedName)) {
            $this->addUseStatement($qualifiedName, $alias);
        }
        return $this->getUseAlias($qualifiedName);
    }

    /**
     * Returns whether the given use statement is present
     *
     * @param string $qualifiedName
     * @return bool
     */
    public function hasUseStatement(string $qualifiedName): bool
    {
        return $this->useStatements->contains($qualifiedName);
    }

    /**
     * Returns the usable alias for a qualified name
     *
     * @param string $qualifiedName
     * @return string the alias
     */
    public function getUseAlias(string $qualifiedName): string
    {
        return $this->useStatements->getKey($qualifiedName);
    }

    /**
     * Removes a use statement
     *
     * @param string $qualifiedName
     * @return $this
     */
    public function removeUseStatement(string $qualifiedName): self
    {
        $this->useStatements->remove($this->useStatements->getKey($qualifiedName));
        return $this;
    }

    /**
     * Returns all use statements
     *
     * @return Map collection of use statements
     */
    public function getUseStatements(): Map
    {
        return $this->useStatements;
    }

    /**
     * Sets a collection of methods
     *
     * @param PhpMethod[] $methods
     * @return $this
     */
    public function setMethods(array $methods): self
    {
        foreach ($this->methods as $method) {
            $method->setParent(null);
        }

        $this->methods->clear();
        foreach ($methods as $method) {
            $this->setMethod($method);
        }

        return $this;
    }

    /**
     * Adds a method
     *
     * @param PhpMethod $method
     * @return $this
     */
    public function setMethod(PhpMethod $method): self
    {
        $method->setParent($this);
        $this->methods->set($method->getName(), $method);

        return $this;
    }

    /**
     * Removes a method
     *
     * @param string $name method name or Method instance
     * @return $this
     * @throws \InvalidArgumentException If the method does not exist
     */
    public function removeMethodByName(string $name): self
    {
        if (!$this->methods->has($name)) {
            throw new \InvalidArgumentException(sprintf('The method "%s" does not exist.', $name));
        }
        $m = $this->methods->get($name);
        $m->setParent(null);
        $this->methods->remove($name);

        return $this;
    }

    /**
     * Removes a method
     *
     * @param PhpMethod $method Method instance
     * @return $this
     */
    public function removeMethod(PhpMethod $method): self
    {
        $this->removeMethodByName($method->getName());
        return $this;
    }

    /**
     * Checks whether a method exists or not
     *
     * @param PhpMethod $method
     * @return bool `true` if it exists and `false` if not
     */
    public function hasMethod(PhpMethod $method): bool
    {
        return $this->hasMethodByName($method->getName());
    }

    /**
     * Checks whether a method exists or not
     *
     * @param string $name
     * @return bool
     */
    public function hasMethodByName(string $name): bool
    {
        return $this->methods->has($name);
    }

    /**
     * Returns a method
     *
     * @param PhpMethod $method
     * @throws \InvalidArgumentException if the method cannot be found
     * @return PhpMethod
     */
    public function getMethod(PhpMethod $method)
    {
        return $this->getMethodByName($method->getName());
    }

    /**
     * Returns amethod
     *
     * @param string $name
     * @return PhpMethod
     * @throws \InvalidArgumentException If the method does not exists
     */
    public function getMethodByName(string $name): PhpMethod
    {
        if (!$this->methods->has($name)) {
            throw new \InvalidArgumentException(sprintf('The method "%s" does not exist.', $name));
        }

        return $this->methods->get($name);
    }

    /**
     * Returns all methods
     *
     * @return Map collection of methods
     */
    public function getMethods(): Map
    {
        return $this->methods;
    }

    /**
     * Returns all method names
     *
     * @return Set
     */
    public function getMethodNames(): Set
    {
        return $this->methods->keys();
    }

    /**
     * Clears all methods
     *
     * @return $this
     */
    public function clearMethods(): self
    {
        $this->methods->each(function (string $key, PhpMethod $element) {
            $element->setParent(null);
        });
        $this->methods->clear();

        return $this;
    }

    /**
     * Generates a docblock from provided information
     *
     * @return $this
     */
    public function generateDocblock()
    {
        $docblock = $this->getDocblock();
        $docblock->setShortDescription($this->getDescription());
        $docblock->setLongDescription($this->getLongDescription());

        foreach ($this->methods as $method) {
            $method->generateDocblock();
        }

        return $this;
    }
}
