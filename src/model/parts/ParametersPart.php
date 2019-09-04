<?php declare(strict_types=1);

namespace cristianoc72\codegen\model\parts;

use cristianoc72\codegen\model\PhpParameter;
use gossi\docblock\Docblock;
use gossi\docblock\tags\ParamTag;

/**
 * Parameters Part.
 *
 * For all models that can have parameters
 *
 * @author Thomas Gossmann
 */
trait ParametersPart
{
    /** @var PhpParameter[] */
    private $parameters = [];

    /**
     * Sets a collection of parameters.
     *
     * Note: clears all parameters before setting the new ones
     *
     * @param PhpParameter[] $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = [];
        foreach ($parameters as $parameter) {
            $this->addParameter($parameter);
        }

        return $this;
    }

    /**
     * Adds a parameter.
     *
     * @param PhpParameter $parameter
     *
     * @return $this
     */
    public function addParameter(PhpParameter $parameter): self
    {
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * Checks whether a parameter exists.
     *
     * @param string $name parameter name
     *
     * @return bool `true` if a parameter exists and `false` if not
     */
    public function hasParameter(string $name): bool
    {
        foreach ($this->parameters as $param) {
            if ($param->getName() == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * A quick way to add a parameter which is created from the given parameters.
     *
     * @param string      $name
     * @param null|string $type
     * @param mixed       $defaultValue omit the argument to define no default value
     *
     * @return $this
     */
    public function addSimpleParameter(string $name, ?string $type = null, $defaultValue = null): self
    {
        $parameter = new PhpParameter($name);
        if (null !== $type) {
            $parameter->setType($type);
        }

        if (2 < func_num_args()) {
            $parameter->setValue($defaultValue);
        }

        $this->addParameter($parameter);

        return $this;
    }

    /**
     * A quick way to add a parameter with description which is created from the given parameters.
     *
     * @param string      $name
     * @param null|string $type
     * @param null|string $typeDescription
     * @param mixed       $defaultValue    omit the argument to define no default value
     *
     * @return $this
     */
    public function addSimpleDescParameter(string $name, ?string $type = null, ?string $typeDescription = null, $defaultValue = null): self
    {
        $parameter = new PhpParameter($name);
        $parameter->setType($type ?? '');
        $parameter->setTypeDescription($typeDescription ?? '');

        if (3 < func_num_args() == 3) {
            $parameter->setValue($defaultValue);
        }

        $this->addParameter($parameter);

        return $this;
    }

    /**
     * Returns a parameter by index or name.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return PhpParameter
     */
    public function getParameterByName(string $name): PhpParameter
    {
        foreach ($this->parameters as $param) {
            if ($param->getName() === $name) {
                return $param;
            }
        }

        throw new \InvalidArgumentException(sprintf('There is no parameter named "%s".', $name));
    }

    /**
     * Returns a parameter by index or name.
     *
     * @param int $position
     *
     * @throws \InvalidArgumentException
     *
     * @return PhpParameter
     */
    public function getParameterByPosition(int $position): PhpParameter
    {
        $this->checkPosition($position);

        return $this->parameters[$position];
    }

    /**
     * Replaces a parameter at a given position.
     *
     * @param int          $position
     * @param PhpParameter $parameter
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function replaceParameter(int $position, PhpParameter $parameter): self
    {
        $this->checkPosition($position);
        $this->parameters[$position] = $parameter;

        return $this;
    }

    /**
     * Remove the given parameter.
     *
     * @param PhpParameter $param
     *
     * @throws \InvalidArgumentException If the parameter doesn't exist
     *
     * @return $this
     */
    public function removeParameter(PhpParameter $param): self
    {
        return $this->removeParameterByName($param->getName());
    }

    /**
     * Remove the parameter at the given position.
     *
     * @param int $position
     *
     * @throws \InvalidArgumentException If the parameter doesn't exist
     *
     * @return $this
     */
    public function removeParameterByPosition(int $position): self
    {
        $this->checkPosition($position);
        unset($this->parameters[$position]);
        $this->parameters = array_values($this->parameters);

        return $this;
    }

    /**
     * Remove a parameter having the given name.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException If the parameter doesn't exist
     *
     * @return $this
     */
    public function removeParameterByName(string $name): self
    {
        $position = null;
        foreach ($this->parameters as $index => $param) {
            if ($param->getName() == $name) {
                $position = $index;
            }
        }

        if ($position !== null) {
            $this->removeParameterByPosition($position);
        }

        return $this;
    }

    /**
     * @param int $position
     *
     * @throws \InvalidArgumentException if the position is not correct
     */
    private function checkPosition(int $position): void
    {
        if ($position < 0 || $position > count($this->parameters)) {
            throw new \InvalidArgumentException(sprintf('The position must be in the range [0, %d].', count($this->parameters)));
        }
    }

    /**
     * Returns an array of parameters.
     *
     * @return PhpParameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Returns the docblock.
     *
     * @return Docblock
     */
    abstract protected function getDocblock(): Docblock;

    /**
     * Generates docblock for params.
     *
     * @psalm-suppress TooManyArguments
     */
    protected function generateParamDocblock(): void
    {
        $docblock = $this->getDocblock();
        $tags = $docblock->getTags('param');
        foreach ($this->parameters as $param) {
            $ptag = $param->getDocblockTag();

            $tag = $tags->find($ptag, function (ParamTag $tag, ParamTag $ptag): bool {
                return $tag->getVariable() == $ptag->getVariable();
            });

            // try to update existing docblock first
            if ($tag !== null) {
                $tag->setDescription($ptag->getDescription());
                $tag->setType($ptag->getType());
            }

            // ... append if it doesn't exist
            else {
                $docblock->appendTag($ptag);
            }
        }
    }
}
