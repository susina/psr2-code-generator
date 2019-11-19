<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\model;

use cristianoc72\codegen\model\PhpProperty;
use cristianoc72\codegen\tests\parts\ValueTests;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @group model
 */
class PropertyTest extends TestCase
{
    use ValueTests;

    public function testSetGetValue(): void
    {
        $prop = new PhpProperty('needsName');

        $this->assertNull($prop->getValue());
        $this->assertFalse($prop->hasValue());
        $this->assertSame($prop, $prop->setValue('foo'));
        $this->assertEquals('foo', $prop->getValue());
        $this->assertTrue($prop->hasValue());
        $this->assertSame($prop, $prop->unsetValue());
        $this->assertNull($prop->getValue());
        $this->assertFalse($prop->hasValue());
    }

    public function testSetGetExpression(): void
    {
        $prop = new PhpProperty('needsName');

        $this->assertEquals('', $prop->getExpression());
        $this->assertFalse($prop->isExpression());
        $this->assertSame($prop, $prop->setExpression('null'));
        $this->assertEquals('null', $prop->getExpression());
        $this->assertTrue($prop->isExpression());
        $this->assertSame($prop, $prop->unsetExpression());
        $this->assertEquals('', $prop->getExpression());
        $this->assertFalse($prop->isExpression());
    }

    public function testValueAndExpression(): void
    {
        $prop = new PhpProperty('needsName');

        $prop->setValue('abc');
        $prop->setExpression('null');

        $this->assertTrue($prop->hasValue());
        $this->assertTrue($prop->isExpression());
    }

    
    public function testValues(): void
    {
        $this->isValueString(PhpProperty::create('x')->setValue('hello'));
        $this->isValueInteger(PhpProperty::create('x')->setValue(2));
        $this->isValueFloat(PhpProperty::create('x')->setValue(0.2));
        $this->isValueBool(PhpProperty::create('x')->setValue(false));
        $this->isValueNull(PhpProperty::create('x')->setValue(null));
    }

    public function testInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        PhpProperty::create('x')->setValue(new stdClass());
    }
}
