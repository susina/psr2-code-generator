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

namespace Susina\Codegen\Model;

use gossi\docblock\tags\ParamTag;
use Susina\Codegen\Model\Parts\NamePart;
use Susina\Codegen\Model\Parts\TypePart;
use Susina\Codegen\Model\Parts\ValuePart;

/**
 * Represents a PHP parameter.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Thomas Gossmann
 */
class PhpParameter extends AbstractModel implements ValueInterface
{
    use NamePart;
    use TypePart;
    use ValuePart;

    /** @var bool */
    private $passedByReference = false;

    /**
     * Creates a new PHP parameter.
     *
     * @param string $name the parameter name
     */
    final public function __construct(string $name = '')
    {
        $this->setName($name);
    }

    /**
     * Creates a new PHP parameter.
     *
     * @param string $name the parameter name
     *
     * @return static
     */
    public static function create(string $name = '')
    {
        return new static($name);
    }

    /**
     * Sets whether this parameter is passed by reference.
     *
     * @param bool $bool `true` if passed by reference and `false` if not
     *
     * @return $this
     */
    public function setPassedByReference(bool $bool): self
    {
        $this->passedByReference = (bool) $bool;

        return $this;
    }

    /**
     * Returns whether this parameter is passed by reference.
     *
     * @return bool `true` if passed by reference and `false` if not
     */
    public function isPassedByReference(): bool
    {
        return $this->passedByReference;
    }

    /**
     * Returns a docblock tag for this parameter.
     *
     * @return ParamTag
     */
    public function getDocblockTag()
    {
        return ParamTag::create()
            ->setType($this->getType())
            ->setVariable($this->getName())
            ->setDescription($this->getTypeDescription())
        ;
    }

    /**
     * Alias for setDescription().
     *
     * @see #setDescription
     *
     * @return $this
     */
    public function setTypeDescription(string $description): self
    {
        return $this->setDescription($description);
    }

    /**
     * Alias for getDescription().
     *
     * @see #getDescription
     */
    public function getTypeDescription(): string
    {
        return $this->getDescription();
    }
}
