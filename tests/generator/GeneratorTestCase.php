<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\config\GeneratorConfig;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class GeneratorTestCase extends TestCase
{
    public function getConfig(): GeneratorConfig
    {
        return $this->getMockBuilder(GeneratorConfig::class)->getMock();
    }

    protected function getGeneratedContent(string $file): string
    {
        return file_get_contents(__DIR__.'/../generator/generated/'.$file);
    }
}
