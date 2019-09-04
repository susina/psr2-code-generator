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

use cristianoc72\codegen\model\parts\AbstractPart;
use cristianoc72\codegen\model\parts\BodyPart;
use cristianoc72\codegen\model\parts\FinalPart;
use cristianoc72\codegen\model\parts\ParametersPart;
use cristianoc72\codegen\model\parts\ReferenceReturnPart;
use cristianoc72\codegen\model\parts\TypeDocblockGeneratorPart;
use gossi\docblock\tags\ReturnTag;

/**
 * Represents a PHP method.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Thomas Gossmann
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class PhpMethod extends AbstractPhpMember implements RoutineInterface
{
    use AbstractPart;
    use BodyPart;
    use FinalPart;
    use ParametersPart;
    use ReferenceReturnPart;
    use TypeDocblockGeneratorPart;

    /**
     * Creates a new PHP method.
     *
     * @param string $name the method name
     *
     * @return static
     */
    public static function create(string $name)
    {
        return new static($name);
    }

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    /**
     * Generates docblock based on provided information.
     */
    public function generateDocblock(): self
    {
        $docblock = $this->getDocblock();
        $docblock->setShortDescription($this->getDescription());
        $docblock->setLongDescription($this->getLongDescription());

        // return tag
        $this->generateTypeTag(new ReturnTag());

        // param tags
        $this->generateParamDocblock();

        return $this;
    }
}
