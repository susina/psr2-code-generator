<?php declare(strict_types=1);

namespace Susina\Codegen\Tests\Model;

use PHPUnit\Framework\TestCase;
use Susina\Codegen\Model\PhpConstant;

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
