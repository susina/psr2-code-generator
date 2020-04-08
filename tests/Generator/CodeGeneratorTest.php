<?php declare(strict_types=1);

namespace Susina\Codegen\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Susina\Codegen\Config\GeneratorConfig;
use Susina\Codegen\Generator\CodeGenerator;

class CodeGeneratorTest extends TestCase
{
    public function testConstructor(): void
    {
        $generator = new CodeGenerator(null);
        $this->assertTrue($generator->getConfig() instanceof GeneratorConfig);

        $config = new GeneratorConfig();
        $generator = new CodeGenerator($config);
        $this->assertSame($config, $generator->getConfig());
    }

    public function testPassWrongConfigThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $generator = new CodeGenerator(256);
    }
}
