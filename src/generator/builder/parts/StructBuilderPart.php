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
use cristianoc72\codegen\model\PhpConstant;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpProperty;
use cristianoc72\codegen\model\PhpTrait;
use cristianoc72\codegen\model\PropertiesInterface;
use cristianoc72\codegen\model\TraitsInterface;
use phootwork\lang\Text;

trait StructBuilderPart
{
    abstract protected function ensureBlankLine();

    /**
     * @param AbstractModel $model
     */
    abstract protected function generate(AbstractModel $model);

    /**
     * @param DocblockInterface $model
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
            $this->getWriter()->writeln('namespace '.$namespace.';');
        }
    }

    protected function buildRequiredFiles(AbstractPhpStruct $model): void
    {
        if (!$model->getRequiredFiles()->isEmpty()) {
            $this->ensureBlankLine();
            $model->getRequiredFiles()->each(function (string $element): void {
                $this->getWriter()->writeln('require_once '.var_export($element, true).';');
            });
        }
    }

    protected function buildUseStatements(AbstractPhpStruct $model): void
    {
        if ($model->getUseStatements()->isEmpty()) {
            return;
        }

        $this->ensureBlankLine();
        $model->getUseStatements()->each(function (string $alias, string $value) use ($model) {
            $namespace = new Text($value);
            if (!$namespace->contains('\\') && '' === $model->getNamespace()) {
                //avoid fatal 'The use statement with non-compound name '$commonName' has no effect'
                return;
            }

            $commonName = $namespace->substring((int) $namespace->lastIndexOf('\\'))->trimStart('\\');
            $this->getWriter()->write("use {$namespace}");

            if ($commonName->toString() !== $alias) {
                $this->getWriter()->write(" as {$alias}");
            }

            $this->getWriter()->writeln(';');
        });

        $this->ensureBlankLine();
    }

    protected function buildTraits(TraitsInterface $model): void
    {
        $model->getTraits()->each(function (PhpTrait $element): void {
            $this->getWriter()->write('use ');
            $this->getWriter()->writeln($element->getName().';');
        });
    }

    protected function buildConstants(ConstantsInterface $model): void
    {
        $model->getConstants()->each(function (string $key, PhpConstant $element): void {
            $this->generate($element);
        });
    }

    protected function buildProperties(PropertiesInterface $model): void
    {
        $model->getProperties()->each(function (string $key, PhpProperty $element): void {
            $this->generate($element);
        });
    }

    protected function buildMethods(AbstractPhpStruct $model): void
    {
        $model->getMethods()->each(function (string $key, PhpMethod $element): void {
            $this->generate($element);
        });
    }

    private function sortUseStatements(AbstractPhpStruct $model): void
    {
        if ($this->getConfig()->isSortingEnabled()
                && false !== ($useStatementSorting = $this->getConfig()->getUseStatementSorting())) {
            if (is_string($useStatementSorting) || true === $useStatementSorting) {
                $useStatementSorting = new DefaultUseStatementComparator();
            }
            $model->getUseStatements()->sort($useStatementSorting);
        }
    }

    private function sortConstants(ConstantsInterface $model): void
    {
        if ($this->getConfig()->isSortingEnabled()
                && false !== ($constantSorting = $this->getConfig()->getConstantSorting())) {
            if (is_string($constantSorting) || true === $constantSorting) {
                $constantSorting = new DefaultConstantComparator();
            }
            $model->getConstants()->sort($constantSorting);
        }
    }

    private function sortProperties(PropertiesInterface $model): void
    {
        if ($this->getConfig()->isSortingEnabled()
                && false !== ($propertySorting = $this->getConfig()->getPropertySorting())) {
            if (is_string($propertySorting) || true === $propertySorting) {
                $propertySorting = new DefaultPropertyComparator();
            }
            $model->getProperties()->sort($propertySorting);
        }
    }

    private function sortMethods(AbstractPhpStruct $model): void
    {
        if ($this->getConfig()->isSortingEnabled()
                && false !== ($methodSorting = $this->getConfig()->getMethodSorting())) {
            if (is_string($methodSorting) || true === $methodSorting) {
                $methodSorting = new DefaultMethodComparator();
            }
            $model->getMethods()->sort($methodSorting);
        }
    }
}
