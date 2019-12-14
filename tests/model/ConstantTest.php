<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\model;

use cristianoc72\codegen\model\PhpConstant;
use PHPUnit\Framework\TestCase;

class ConstantTest extends TestCase
{
    public function testConstantExpression(): void
    {
        $const = new PhpConstant('great', 'beautiful(expression)', true);

        $this->assertTrue($const->isExpression());
        $this->assertEquals('beautiful(expression)', $const->getExpression());
        $this->assertNull($const->getValue());
    }
}
