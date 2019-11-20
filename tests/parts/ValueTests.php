<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\parts;

use cristianoc72\codegen\model\ValueInterface;

trait ValueTests
{
    protected function isValueString(ValueInterface $obj): void
    {
        $this->assertTrue(is_string($obj->getValue()));
    }

    protected function isValueInteger(ValueInterface $obj): void
    {
        $this->assertTrue(is_int($obj->getValue()));
    }

    protected function isValueFloat(ValueInterface $obj): void
    {
        $this->assertTrue(is_float($obj->getValue()));
    }

    protected function isValueNumber(ValueInterface $obj): void
    {
        $this->assertTrue(is_numeric($obj->getValue()));
    }

    protected function isValueBool(ValueInterface $obj): void
    {
        $this->assertTrue(is_bool($obj->getValue()));
    }

    protected function isValueNull(ValueInterface $obj): void
    {
        $this->assertNull($obj->getValue());
    }
}
