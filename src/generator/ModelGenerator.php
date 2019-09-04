<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator;

use cristianoc72\codegen\config\GeneratorConfig;
use cristianoc72\codegen\generator\utils\Writer;
use cristianoc72\codegen\model\AbstractModel;

/**
 * Model Generator.
 *
 * @author Thomas Gossmann
 * @author Cristiano Cinotti
 */
class ModelGenerator
{
    /** @var Writer */
    private $writer;

    /** @var BuilderFactory */
    private $factory;

    /** @var GeneratorConfig */
    private $config;

    /**
     * @param GeneratorConfig $config
     */
    public function __construct(GeneratorConfig $config)
    {
        $this->config = $config;
        $this->writer = new Writer();
        $this->factory = new BuilderFactory($this);
    }

    /**
     * @return GeneratorConfig
     */
    public function getConfig(): GeneratorConfig
    {
        return $this->config;
    }

    /**
     * @return Writer
     */
    public function getWriter(): Writer
    {
        return $this->writer;
    }

    /**
     * @return BuilderFactory
     */
    public function getFactory(): BuilderFactory
    {
        return $this->factory;
    }

    /**
     * @param AbstractModel $model
     *
     * @return string
     */
    public function generate(AbstractModel $model): string
    {
        $this->writer->reset();

        $builder = $this->factory->getBuilder($model);
        $builder->build($model);

        return $this->writer->getContent();
    }
}
