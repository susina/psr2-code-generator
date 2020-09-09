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

use gossi\docblock\tags\VarTag;
use Susina\Codegen\Model\Parts\TypeDocblockGeneratorPart;
use Susina\Codegen\Model\Parts\ValuePart;

/**
 * Represents a PHP property.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Thomas Gossmann
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class PhpProperty extends AbstractPhpMember implements ValueInterface
{
    use TypeDocblockGeneratorPart;
    use ValuePart;

    /**
     * PhpProperty constructor.
     * This final method is needed to put in safe the function `create`.
     *
     * @see https://psalm.dev/docs/running_psalm/issues/UnsafeInstantiation/
     */
    final public function __construct(string $name = '')
    {
        return parent::__construct($name);
    }

    /**
     * Creates a new PHP property.
     *
     * @param string $name the properties name
     *
     * @return static
     */
    public static function create(string $name = '')
    {
        return new static($name);
    }

    /**
     * Generates docblock based on provided information.
     */
    public function generateDocblock()
    {
        $docblock = $this->getDocblock();
        $docblock->setShortDescription($this->getDescription());
        $docblock->setLongDescription($this->getLongDescription());

        // var tag
        $this->generateTypeTag(new VarTag());

        return $this;
    }
}
