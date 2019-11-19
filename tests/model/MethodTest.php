<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\model;

use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpParameter;
use PHPUnit\Framework\TestCase;

/**
 * @group model
 */
class MethodTest extends TestCase
{
    public function testParameters(): void
    {
        $method = new PhpMethod('needsName');

        $this->assertEquals([], $method->getParameters());
        $this->assertSame($method, $method->setParameters($params = [
            new PhpParameter('a'),
        ]));
        $this->assertSame($params, $method->getParameters());

        $this->assertSame($method, $method->addParameter($param = new PhpParameter('b')));
        $this->assertSame($param, $method->getParameterByName('b'));
        $this->assertSame($param, $method->getParameterByPosition(1));
        $params[] = $param;
        $this->assertSame($params, $method->getParameters());

        $this->assertSame($method, $method->removeParameterByPosition(0));
        $this->assertEquals('b', $method->getParameterByPosition(0)->getName());

        unset($params[0]);
        $this->assertEquals([
            $param,
        ], $method->getParameters());

        $this->assertSame($method, $method->addParameter($param = new PhpParameter('c')));
        $params[] = $param;
        $params = array_values($params);
        $this->assertEquals($params, $method->getParameters());

        $this->assertSame($method, $method->replaceParameter(0, $param = new PhpParameter('a')));
        $params[0] = $param;
        $this->assertEquals($params, $method->getParameters());

        $method->removeParameter($param);
        $method->removeParameterByName('c');
        $this->assertEquals([], $method->getParameters());
    }

    public function testGetNonExistentParameterByName(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $method = new PhpMethod('doink');
        $method->getParameterByName('x');
    }

    public function testGetNonExistentParameterByIndex(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $method = new PhpMethod('doink');
        $method->getParameterByPosition(5);
    }

    public function testReplaceNonExistentParameterByIndex(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $method = new PhpMethod('doink');
        $method->replaceParameter(5, new PhpParameter());
    }

    public function testRemoveNonExistentParameterByIndex(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $method = new PhpMethod('doink');
        $method->removeParameterByPosition(5);
    }

    public function testBody(): void
    {
        $method = new PhpMethod('needsName');

        $this->assertSame('', $method->getBody());
        $this->assertSame($method, $method->setBody('foo'));
        $this->assertEquals('foo', $method->getBody());
        $this->assertSame($method, $method->appendToBody(' appended bar'));
        $this->assertEquals('foo appended bar', $method->getBody());
    }

    public function testReferenceReturned(): void
    {
        $method = new PhpMethod('needsName');

        $this->assertFalse($method->isReferenceReturned());
        $this->assertSame($method, $method->setReferenceReturned(true));
        $this->assertTrue($method->isReferenceReturned());
        $this->assertSame($method, $method->setReferenceReturned(false));
        $this->assertFalse($method->isReferenceReturned());
    }
}
