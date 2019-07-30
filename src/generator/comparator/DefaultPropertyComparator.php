<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator\comparator;

use cristianoc72\codegen\model\PhpProperty;
use phootwork\lang\Comparator;

/**
 * Default property comparator
 *
 * Orders them by visibility first then by method name
 */
class DefaultPropertyComparator extends AbstractMemberComparator
{

    /**
     * @param PhpProperty $a
     * @param PhpProperty $b
     *
     * @return int
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function compare($a, $b): int
    {
        return $this->compareMembers($a, $b);
    }
}
