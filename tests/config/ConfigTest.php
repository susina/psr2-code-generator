<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\config;

use cristianoc72\codegen\config\GeneratorConfig;
use cristianoc72\codegen\generator\CodeGenerator;
use gossi\docblock\Docblock;
use phootwork\lang\ComparableComparator;
use phootwork\lang\Comparator;
use PHPUnit\Framework\TestCase;

/**
 * @group config
 */
class ConfigTest extends TestCase
{
    public function testGeneratorConfigDefaults()
    {
        $config = new GeneratorConfig();

        $this->assertFalse($config->getGenerateEmptyDocblock());
        $this->assertTrue($config->isSortingEnabled());
        $this->assertEquals(CodeGenerator::SORT_USESTATEMENTS_DEFAULT, $config->getUseStatementSorting());
        $this->assertEquals(CodeGenerator::SORT_CONSTANTS_DEFAULT, $config->getConstantSorting());
        $this->assertEquals(CodeGenerator::SORT_PROPERTIES_DEFAULT, $config->getPropertySorting());
        $this->assertEquals(CodeGenerator::SORT_METHODS_DEFAULT, $config->getMethodSorting());
        $this->assertNull($config->getHeaderComment());
        $this->assertNull($config->getHeaderDocblock());
    }

    public function testGeneratorConfigSetters()
    {
        $config = new GeneratorConfig();

        $this->assertFalse($config->getGenerateEmptyDocblock());

        $config->setGenerateEmptyDocblock(true);
        $this->assertTrue($config->getGenerateEmptyDocblock());

        $config->setGenerateEmptyDocblock(false);
        $this->assertFalse($config->getGenerateEmptyDocblock());

        $config->setUseStatementSorting(false);
        $this->assertFalse($config->getUseStatementSorting());

        $config->setConstantSorting('abc');
        $this->assertEquals('abc', $config->getConstantSorting());

        $config->setPropertySorting(new ComparableComparator());
        $this->assertTrue($config->getPropertySorting() instanceof Comparator);

        $cmp = function ($a, $b) {
            return strcmp($a, $b);
        };
        $config->setMethodSorting($cmp);
        $this->assertSame($cmp, $config->getMethodSorting());

        $config->setSortingEnabled(false);
        $this->assertFalse($config->isSortingEnabled());

        $this->assertEquals('hello world', $config->setHeaderComment('hello world')->getHeaderComment()->getShortDescription());

        $docblock = new Docblock();
        $this->assertSame($docblock, $config->setHeaderDocblock($docblock)->getHeaderDocblock());
    }
}
