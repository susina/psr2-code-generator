<?php
namespace cristianoc72\codegen\tests\fixtures;

/**
 * Dummy docblock
 */
trait DummyTrait
{
    use VeryDummyTrait;
    
    private $iAmHidden;
    
    public function foo()
    {
    }
}
