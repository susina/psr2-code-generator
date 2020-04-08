<?php declare(strict_types=1);

namespace Susina\Codegen\Generator\Comparator;

use phootwork\lang\Comparator;
use Susina\Codegen\Model\PhpProperty;

/**
 * Default property comparator.
 *
 * Orders them by visibility first then by method name
 */
class DefaultPropertyComparator extends AbstractMemberComparator
{
    /**
     * @param PhpProperty $a
     * @param PhpProperty $b
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function compare($a, $b): int
    {
        return $this->compareMembers($a, $b);
    }
}
