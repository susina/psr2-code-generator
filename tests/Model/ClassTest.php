<?php declare(strict_types=1);

namespace Susina\Codegen\Tests\Model;

use PHPUnit\Framework\TestCase;
use Susina\Codegen\Model\PhpClass;
use Susina\Codegen\Model\PhpConstant;
use Susina\Codegen\Model\PhpInterface;
use Susina\Codegen\Model\PhpProperty;
use Susina\Codegen\Model\PhpTrait;
use Susina\Codegen\Tests\Parts\ModelAssertions;

class ClassTest extends TestCase
{
    use ModelAssertions;

    public function testConstants(): void
    {
        $class = new PhpClass();

        $this->assertTrue($class->getConstants()->isEmpty());
        $this->assertSame($class, $class->setConstants([
            'foo' => 'bar',
            new PhpConstant('rabimmel', 'rabammel'),
        ]));
        $this->assertTrue($class->hasConstantByName('rabimmel'));
        $this->assertEquals(['foo', 'rabimmel'], $class->getConstantNames()->toArray());
        $this->assertEquals('bar', $class->getConstant('foo')->getValue());
        $this->assertSame($class, $class->setConstantByName('bar', 'baz'));
        $this->assertEquals(['foo', 'rabimmel', 'bar'], $class->getConstantNames()->toArray());
        $this->assertEquals(3, $class->getConstants()->size());
        $this->assertSame($class, $class->removeConstantByName('foo'));
        $this->assertEquals(['rabimmel', 'bar'], $class->getConstantNames()->toArray());
        $this->assertSame($class, $class->setConstant($bim = new PhpConstant('bim', 'bam')));
        $this->assertTrue($class->hasConstantByName('bim'));
        $this->assertSame($bim, $class->getConstant('bim'));
        $this->assertTrue($class->hasConstant($bim));
        $this->assertSame($class, $class->removeConstant($bim));
        $this->assertFalse($class->hasConstant($bim));

        $this->assertFalse($class->getConstants()->isEmpty());
        $class->clearConstants();
        $this->assertTrue($class->getConstants()->isEmpty());

        $class->setConstantByName('FOO', 'bar');
        $this->assertEquals('bar', $class->getConstant('FOO')->getValue());
        $class->setConstantByName('NMBR', 300, true);
        $this->assertEquals(300, $class->getConstant('NMBR')->getValue());
        $this->assertFalse($class->getConstant('NMBR')->isExpression());
        $this->assertEquals('', $class->getConstant('NMBR')->getExpression());

        try {
            $this->assertEmpty($class->getConstant('constant-not-found'));
        } catch (\InvalidArgumentException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testRemoveConstantThrowsExceptionWhenConstantDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $class = new PhpClass();
        $class->removeConstantByName('foo');
    }

    public function testGetConstantThrowsExceptionWhenConstantDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $class = new PhpClass();
        $class->getConstant('foo');
    }

    public function testAbstract(): void
    {
        $class = new PhpClass();

        $this->assertFalse($class->isAbstract());
        $this->assertSame($class, $class->setAbstract(true));
        $this->assertTrue($class->isAbstract());
        $this->assertSame($class, $class->setAbstract(false));
        $this->assertFalse($class->isAbstract());
    }

    public function testFinal(): void
    {
        $class = new PhpClass();

        $this->assertFalse($class->isFinal());
        $this->assertSame($class, $class->setFinal(true));
        $this->assertTrue($class->isFinal());
        $this->assertSame($class, $class->setFinal(false));
        $this->assertFalse($class->isFinal());
    }

    public function testParentClassName(): void
    {
        $class = new PhpClass();

        $this->assertEquals('', $class->getParentClassName());
        $this->assertSame($class, $class->setParentClassName('stdClass'));
        $this->assertEquals('stdClass', $class->getParentClassName());
        $this->assertSame($class, $class->setParentClassName(null));
        $this->assertEquals('', $class->getParentClassName());
    }

    public function testInterfaces(): void
    {
        $class = new PhpClass('my\name\space\Class');

        $this->assertFalse($class->hasInterfaces());
        $this->assertTrue($class->getInterfaces()->isEmpty());
        $this->assertSame($class, $class->setInterfaces([
            'foo',
            'bar',
        ]));
        $this->assertEquals([
            'foo',
            'bar',
        ], $class->getInterfaces()->toArray());
        $this->assertSame($class, $class->addInterface('stdClass'));
        $this->assertEquals([
            'foo',
            'bar',
            'stdClass',
        ], $class->getInterfaces()->toArray());
        $this->assertTrue($class->hasInterfaces());

        $interface = new PhpInterface('my\name\space\Interface');
        $class->addInterface($interface);
        $this->assertTrue($class->hasInterfaceByName('my\name\space\Interface'));
        $this->assertSame($class, $class->removeInterface($interface));

        $class->addInterface(new PhpInterface('other\name\space\Interface'));
        $this->assertTrue($class->hasUseStatement('other\name\space\Interface'));
        $this->assertSame($class, $class->removeInterfaceByName('other\name\space\Interface'));
        $this->assertTrue($class->hasUseStatement('other\name\space\Interface'));
    }

    public function testTraits(): void
    {
        $class = new PhpClass('my\name\space\Class');

        $this->assertEquals([], $class->getTraits()->toArray());
        $this->assertSame($class, $class->setTraits([
            'foo',
            'bar',
        ]));
        $this->assertTrue($class->hasTraitByName('foo'));
        $this->assertTrue($class->hasTraitByName('bar'));
        $fooTrait = new PhpTrait('foo');
        $barTrait = new PhpTrait('bar');
        $stdTrait = new PhpTrait('stdClass');
        $this->assertEquals([$fooTrait, $barTrait], $class->getTraits()->toArray());
        $this->assertSame($class, $class->addTrait($stdTrait));
        $this->assertEquals([$fooTrait, $barTrait, $stdTrait], $class->getTraits()->toArray());

        $trait = new PhpTrait('my\name\space\Trait');
        $class->addTrait($trait);
        $this->assertTrue($class->hasTrait($trait));
        $this->assertFalse($class->getUseStatements()->contains('my\name\space\\'), 'No use statement added since it\'s the same namespace');
        $this->assertSame($class, $class->removeTrait($trait));

        $class->addTrait(new PhpTrait('other\name\space\Trait'));
        $this->assertTrue($class->hasUseStatement('other\name\space\Trait'));
        $this->assertSame($class, $class->removeTraitByName('other\name\space\Trait'));
        $this->assertFalse($class->hasUseStatement('other\name\space\Trait'), 'Use statement removed.');
    }

    public function testSetTraitsWrongTypeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $class = new PhpClass('my\name\space\Class');

        $this->assertEquals([], $class->getTraits()->toArray());
        $this->assertSame($class, $class->setTraits([
            true,
            128,
        ]));
    }

    public function testProperties(): void
    {
        $class = new PhpClass();

        $this->assertTrue($class->getProperties()->isEmpty());
        $this->assertSame($class, $class->setProperty($prop = new PhpProperty('foo')));
        $this->assertSame(['foo' => $prop], $class->getProperties()->toArray());
        $this->assertTrue($class->hasPropertyByName('foo'));
        $this->assertSame($class, $class->removePropertyByName('foo'));
        $this->assertTrue($class->getProperties()->isEmpty());

        $prop = new PhpProperty('bam');
        $class->setProperty($prop);
        $this->assertTrue($class->hasProperty($prop));
        $this->assertSame($class, $class->removeProperty($prop));

        $class->setProperty($orphaned = new PhpProperty('orphaned'));
        $this->assertSame($class, $orphaned->getParent());
        $this->assertSame($orphaned, $class->getProperty('orphaned'));
        $this->assertTrue($class->hasProperty($orphaned));
        $this->assertSame($class, $class->setProperties([
            $prop,
            $prop2 = new PhpProperty('bar'),
        ]));
        $this->assertSame([
            'bam' => $prop,
            'bar' => $prop2,
        ], $class->getProperties()->toArray());
        $this->assertEquals(['bam', 'bar'], $class->getPropertyNames()->toArray());
        $this->assertNull($orphaned->getParent());

        $this->assertFalse($class->getProperties()->isEmpty());
        $class->clearProperties();
        $this->assertTrue($class->getProperties()->isEmpty());

        try {
            $this->assertEmpty($class->getProperty('prop-not-found'));
        } catch (\InvalidArgumentException $e) {
            $this->assertNotNull($e);
        }
    }

    public function testRemoveNonExistentProperty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $class = new PhpClass();
        $class->removePropertyByName('haha');
    }

    public function testLongDescription(): void
    {
        $class = new PhpClass();

        $this->assertSame($class, $class->setLongDescription('very long description'));
        $this->assertEquals('very long description', $class->getLongDescription());
    }

    public function testMultilineDescripion(): void
    {
        $class = new PhpClass();
        $class->setMultilineDescription(['multiline', 'description']);
        $this->assertEquals("multiline\ndescription", $class->getDescription());
    }
}
