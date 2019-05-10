<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator;

use cristianoc72\codegen\generator\comparator\DefaultConstantComparator;
use cristianoc72\codegen\generator\comparator\DefaultMethodComparator;
use cristianoc72\codegen\generator\comparator\DefaultPropertyComparator;
use cristianoc72\codegen\generator\comparator\DefaultUseStatementComparator;
use phootwork\lang\Comparator;

class ComparatorFactory
{

    /**
     * Creates a comparator for use statements
     *
     * @deprecated Instantiate the comparator
     * @return Comparator
     */
    public static function createUseStatementComparator()
    {
        return new DefaultUseStatementComparator();
    }
    
    /**
     * Creates a comparator for constants
     *
     * @deprecated Instantiate the comparator
     * @param string $type
     * @return Comparator
     */
    public static function createConstantComparator()
    {
        return new DefaultConstantComparator();
    }
    
    /**
     * Creates a comparator for properties
     *
     * @deprecated Instantiate the comparator
     * @param string $type
     * @return Comparator
     */
    public static function createPropertyComparator()
    {
        return new DefaultPropertyComparator();
    }
    
    /**
     * Creates a comparator for methods
     *
     * @deprecated Instantiate the comparator
     * @param string $type
     * @return Comparator
     */
    public static function createMethodComparator()
    {
        return new DefaultMethodComparator();
    }
}
