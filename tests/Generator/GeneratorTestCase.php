<?php declare(strict_types=1);

namespace Susina\Codegen\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Susina\Codegen\Config\GeneratorConfig;

class GeneratorTestCase extends TestCase
{
    public function getConfig(): GeneratorConfig
    {
        return $this->getMockBuilder(GeneratorConfig::class)->getMock();
    }

    protected function getGeneratedContent(string $file): string
    {
        return file_get_contents(__DIR__.'/../Generator/Generated/'.$file);
    }
}
