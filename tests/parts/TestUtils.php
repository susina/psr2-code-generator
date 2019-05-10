<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\parts;

trait TestUtils
{
    private function getGeneratedContent(string $file)
    {
        return file_get_contents(__DIR__ . '/../generator/generated/' . $file);
    }
}
