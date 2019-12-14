<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\model;

use cristianoc72\codegen\model\AbstractPhpMember;
use PHPUnit\Framework\TestCase;

class AbstractPhpMemberTest extends TestCase
{
    public function testSetGetStatic(): void
    {
        $member = $this->getMember();

        $this->assertFalse($member->isStatic());
        $this->assertSame($member, $member->setStatic(true));
        $this->assertTrue($member->isStatic());
        $this->assertSame($member, $member->setStatic(false));
        $this->assertFalse($member->isStatic());
    }

    public function testSetGetVisibility(): void
    {
        $member = $this->getMember();

        $this->assertEquals('public', $member->getVisibility());
        $this->assertSame($member, $member->setVisibility('private'));
        $this->assertEquals('private', $member->getVisibility());
    }

    public function testSetVisibilityThrowsExOnInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $member = $this->getMember();
        $member->setVisibility('foo');
    }

    public function testSetGetName(): void
    {
        $member = $this->getMember();

        $this->assertNotNull($member->getName());
        $this->assertSame($member, $member->setName('foo'));
        $this->assertEquals('foo', $member->getName());
    }

    public function testSetGetDocblock(): void
    {
        $member = $this->getMember();

        $this->assertNotNull($member->getDocblock());
        $this->assertSame($member, $member->setDocblock('foo'));
        $this->assertEquals('foo', $member->getDocblock()->getShortDescription());
    }

    private function getMember(): AbstractPhpMember
    {
        return $this->getMockForAbstractClass('cristianoc72\codegen\model\AbstractPhpMember', [
            '__not_null__',
        ]);
    }
}
