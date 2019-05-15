<?php declare(strict_types=1);
namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\config\GeneratorConfig;
use cristianoc72\codegen\generator\CodeGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class CodeGeneratorTest extends TestCase
{
    public function testConstructor()
    {
        $generator = new CodeGenerator(null);
        $this->assertTrue($generator->getConfig() instanceof GeneratorConfig);
        
        $config = new GeneratorConfig();
        $generator = new CodeGenerator($config);
        $this->assertSame($config, $generator->getConfig());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPassWrongConfigThrowsException()
    {
        $generator = new CodeGenerator(256);
    }
}
