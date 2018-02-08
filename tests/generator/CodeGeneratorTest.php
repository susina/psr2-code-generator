<?php
namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\config\CodeGeneratorConfig;
use cristianoc72\codegen\generator\CodeGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @group generator
 */
class CodeGeneratorTest extends TestCase
{
    public function testConfig()
    {
        $generator = new CodeGenerator(null);
        $this->assertTrue($generator->getConfig() instanceof CodeGeneratorConfig);
        
        $config = new CodeGeneratorConfig();
        $generator = new CodeGenerator($config);
        $this->assertSame($config, $generator->getConfig());
    }
}
