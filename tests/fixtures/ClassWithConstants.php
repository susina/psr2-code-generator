<?php
namespace cristianoc72\codegen\tests\fixtures;

class ClassWithConstants
{
    const BAR = self::FOO;
    
    const FOO = 'bar';

    const NMBR = 300;
}