<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Comparator;

use phootwork\lang\Comparator;
use Susina\Codegen\Model\PhpMethod;

/**
 * Default property comparator.
 *
 * Orders them by static first, then visibility and last by property name
 */
class DefaultMethodComparator extends AbstractMemberComparator
{
    /**
     * @param PhpMethod $a
     * @param PhpMethod $b
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function compare($a, $b): int
    {
        if ($a->isStatic() !== $isStatic = $b->isStatic()) {
            return $isStatic ? 1 : -1;
        }

        return $this->compareMembers($a, $b);
    }
}
