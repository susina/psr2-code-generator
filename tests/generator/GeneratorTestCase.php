<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\config\GeneratorConfig;
use phootwork\lang\Text;
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
        $content = file_get_contents(__DIR__.'/../generator/generated/'.$file);

        return $this->purgeLineFeed($content);
    }

    protected function isRunningOnWindows(): bool
    {
        $os = new Text(PHP_OS);

        return $os->toUpperCase()->contains('WIN');
    }

    protected function purgeLineFeed(string $string): string
    {
        if ($this->isRunningOnWindows()) {
            $content = new Text($string);
            $string = $content->replace("\r\n", "\n")->toString();
        }

        return $string;
    }
}
