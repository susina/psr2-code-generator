<?php declare(strict_types=1);

namespace Susina\Codegen\Generator;

use gossi\docblock\Docblock;
use Susina\Codegen\Model\AbstractModel;

/**
 * Code file generator.
 *
 * Generates code for a model and puts it into a file with `<?php` statements. Can also
 * generate header comments.
 *
 * @author Thomas Gossmann
 * @author Cristiano Cinotti
 */
class CodeFileGenerator extends CodeGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate(AbstractModel $model): string
    {
        $content = "<?php declare(strict_types=1);\n\n";

        /** @var ?Docblock $comment */
        $comment = $this->getConfig()->getHeaderComment();
        if (null !== $comment && !$comment->isEmpty()) {
            $content .= str_replace('/**', '/*', $comment->toString())."\n";
        }

        /** @var ?Docblock $docblock */
        $docblock = $this->getConfig()->getHeaderDocblock();
        if (null !== $docblock && !$docblock->isEmpty()) {
            $content .= $docblock->toString()."\n";
        }

        $content .= parent::generate($model);

        return $content;
    }
}
