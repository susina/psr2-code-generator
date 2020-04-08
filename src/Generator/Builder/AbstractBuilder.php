<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Builder;

use Susina\Codegen\Config\GeneratorConfig;
use Susina\Codegen\Generator\ModelGenerator;
use Susina\Codegen\Generator\Utils\Writer;
use Susina\Codegen\Model\AbstractModel;
use Susina\Codegen\Model\DocblockInterface;

abstract class AbstractBuilder
{
    /** @var ModelGenerator */
    protected $generator;

    /** @var Writer */
    private $writer;

    /** @var GeneratorConfig */
    private $config;

    public function __construct(ModelGenerator $generator)
    {
        $this->generator = $generator;
        $this->writer = $generator->getWriter();
        $this->config = $generator->getConfig();
    }

    abstract public function build(AbstractModel $model): void;

    protected function getConfig(): GeneratorConfig
    {
        return $this->config;
    }

    protected function getWriter(): Writer
    {
        return $this->writer;
    }

    protected function generate(AbstractModel $model): void
    {
        $builder = $this->generator->getFactory()->getBuilder($model);
        $builder->build($model);
    }

    protected function ensureBlankLine(): void
    {
        if (!$this->writer->endsWith("\n\n") && (strlen($this->writer->rtrim()->getContent()) > 0) &&
            !$this->writer->endsWith("{\n")) {
            $this->writer->writeln();
        }
    }

    protected function buildDocblock(DocblockInterface $model): void
    {
        $this->ensureBlankLine();
        $model->generateDocblock();
        $docblock = $model->getDocblock();
        if (!$docblock->isEmpty() || $this->config->getGenerateEmptyDocblock()) {
            $this->writer->writeln($docblock->toString());
        }
    }
}
