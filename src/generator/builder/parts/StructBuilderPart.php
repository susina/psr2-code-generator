<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\builder\parts;

use cristianoc72\codegen\config\GeneratorConfig;
use cristianoc72\codegen\generator\comparator\DefaultConstantComparator;
use cristianoc72\codegen\generator\comparator\DefaultMethodComparator;
use cristianoc72\codegen\generator\comparator\DefaultPropertyComparator;
use cristianoc72\codegen\generator\comparator\DefaultUseStatementComparator;
use cristianoc72\codegen\generator\utils\Writer;
use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\model\AbstractPhpStruct;
use cristianoc72\codegen\model\ConstantsInterface;
use cristianoc72\codegen\model\DocblockInterface;
use cristianoc72\codegen\model\NamespaceInterface;
use cristianoc72\codegen\model\PhpTrait;
use cristianoc72\codegen\model\PropertiesInterface;
use cristianoc72\codegen\model\TraitsInterface;

trait StructBuilderPart
{
    
    /**
     * @return void
     */
    abstract protected function ensureBlankLine();
    
    /**
     * @param AbstractModel $model
     * @return void
     */
    abstract protected function generate(AbstractModel $model);
    
    /**
     * @param DocblockInterface $model
     * @return void
     */
    abstract protected function buildDocblock(DocblockInterface $model);

    abstract protected function getConfig(): GeneratorConfig;

    abstract protected function getWriter(): Writer;
    
    protected function buildHeader(AbstractPhpStruct $model): void
    {
        $this->buildNamespace($model);
        $this->buildRequiredFiles($model);
        $this->buildUseStatements($model);
        $this->buildDocblock($model);
    }
    
    protected function buildNamespace(NamespaceInterface $model): void
    {
        if ($namespace = $model->getNamespace()) {
            $this->getWriter()->writeln('namespace ' . $namespace . ';');
        }
    }
    
    protected function buildRequiredFiles(AbstractPhpStruct $model): void
    {
        if ($files = $model->getRequiredFiles()) {
            $this->ensureBlankLine();
            foreach ($files as $file) {
                $this->getWriter()->writeln('require_once ' . var_export($file, true) . ';');
            }
        }
    }
    
    protected function buildUseStatements(AbstractPhpStruct $model): void
    {
        if ($useStatements = $model->getUseStatements()) {
            $this->ensureBlankLine();
            foreach ($useStatements as $alias => $namespace) {
                if (false !== $pos = strrpos($namespace, '\\')) {
                    $commonName = substr($namespace, $pos + 1);
                } else {
                    $commonName = $namespace;
                }
    
                if (false === $pos && !$model->getNamespace()) {
                    //avoid fatal 'The use statement with non-compound name '$commonName' has no effect'
                    continue;
                }
    
                $this->getWriter()->write('use ' . $namespace);
    
                if ($commonName !== $alias) {
                    $this->getWriter()->write(' as ' . $alias);
                }
    
                $this->getWriter()->writeln(';');
            }
            $this->ensureBlankLine();
        }
    }
    
    protected function buildTraits(TraitsInterface $model): void
    {
        /** @var PhpTrait $trait */
        foreach ($model->getTraits() as $trait) {
            $this->getWriter()->write('use ');
            $this->getWriter()->writeln($trait->getName() . ';');
        }
    }
    
    protected function buildConstants(ConstantsInterface $model): void
    {
        foreach ($model->getConstants() as $constant) {
            $this->generate($constant);
        }
    }
    
    protected function buildProperties(PropertiesInterface $model): void
    {
        foreach ($model->getProperties() as $property) {
            $this->generate($property);
        }
    }
    
    protected function buildMethods(AbstractPhpStruct $model): void
    {
        foreach ($model->getMethods() as $method) {
            $this->generate($method);
        }
    }
    
    private function sortUseStatements(AbstractPhpStruct $model): void
    {
        if ($this->getConfig()->isSortingEnabled()
                && ($useStatementSorting = $this->getConfig()->getUseStatementSorting()) !== false) {
            if (is_string($useStatementSorting) || true === $useStatementSorting) {
                $useStatementSorting = new DefaultUseStatementComparator();
            }
            $model->getUseStatements()->sort($useStatementSorting);
        }
    }
    
    private function sortConstants(ConstantsInterface $model): void
    {
        if ($this->getConfig()->isSortingEnabled()
                && ($constantSorting = $this->getConfig()->getConstantSorting()) !== false) {
            if (is_string($constantSorting) || true === $constantSorting) {
                $constantSorting = new DefaultConstantComparator();
            }
            $model->getConstants()->sort($constantSorting);
        }
    }
    
    private function sortProperties(PropertiesInterface $model)
    {
        if ($this->getConfig()->isSortingEnabled()
                && ($propertySorting = $this->getConfig()->getPropertySorting()) !== false) {
            if (is_string($propertySorting) || true === $propertySorting) {
                $propertySorting = new DefaultPropertyComparator();
            }
            $model->getProperties()->sort($propertySorting);
        }
    }
        
    private function sortMethods(AbstractPhpStruct $model)
    {
        if ($this->getConfig()->isSortingEnabled()
                && ($methodSorting = $this->getConfig()->getMethodSorting()) !== false) {
            if (is_string($methodSorting) || true === $methodSorting) {
                $methodSorting = new DefaultMethodComparator();
            }
            $model->getMethods()->sort($methodSorting);
        }
    }
}
