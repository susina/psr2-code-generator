<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\config\GeneratorConfig;
use PHPUnit\Framework\TestCase;

class GeneratorTestCase extends TestCase
{
    public function getConfig()
    {
        return $this->getMockBuilder(GeneratorConfig::class)->getMock();
    }

    protected function getGeneratedContent(string $file)
    {
        return file_get_contents(__DIR__.'/../generator/generated/'.$file);
    }
}
