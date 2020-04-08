<?php declare(strict_types=1);

class ClassWithExpression
{
    const FOO = 'BAR';

    public $bembel = ['ebbelwoi' => 'is eh besser', 'als wie' => 'bier'];

    /**
     * @param array $arr
     */
    public function getValue(array $arr = [self::FOO => 'baz'])
    {
    }
}
