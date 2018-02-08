<?php
namespace cristianoc72\codegen\tests\parser;

use cristianoc72\codegen\model\PhpClass;
use cristianoc72\codegen\parser\FileParser;
use cristianoc72\codegen\parser\visitor\ClassParserVisitor;
use PHPUnit\Framework\TestCase;

/**
 * @group parser
 */
class FileParserTest extends TestCase
{
    public function testVisitors()
    {
        $struct = new PhpClass();
        $visitor = new ClassParserVisitor($struct);
        $parser = new FileParser('dummy-file');
        $parser->addVisitor($visitor);
        $this->assertTrue($parser->hasVisitor($visitor));
        $parser->removeVisitor($visitor);
        $this->assertFalse($parser->hasVisitor($visitor));
    }
    
    /**
     * @expectedException phootwork\file\exception\FileNotFoundException
     */
    public function testGetConstantThrowsExceptionWhenConstantDoesNotExist()
    {
        $parser = new FileParser('file-not-found');
        $parser->parse();
    }
}
