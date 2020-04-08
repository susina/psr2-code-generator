<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Comparator;

use phootwork\lang\Comparator;
use Susina\Codegen\Model\PhpConstant;

/**
 * Default property comparator.
 *
 * Orders them by lower cased first, then upper cased
 */
class DefaultConstantComparator implements Comparator
{
    /** @var DefaultUseStatementComparator */
    private $comparator;

    public function __construct()
    {
        $this->comparator = new DefaultUseStatementComparator();
    }

    /**
     * @param PhpConstant $a
     * @param PhpConstant $b
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function compare($a, $b): int
    {
        return $this->comparator->compare($a->getName(), $b->getName());
    }
}
