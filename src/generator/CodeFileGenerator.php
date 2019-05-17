<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator;

use cristianoc72\codegen\config\GeneratorConfig;
use cristianoc72\codegen\model\AbstractModel;
use gossi\docblock\Docblock;

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
     * {@inheritDoc}
     */
    public function generate(AbstractModel $model): string
    {
        $content = "<?php declare(strict_types=1);\n\n";

        /** @var ?Docblock $comment */
        $comment = $this->getConfig()->getHeaderComment();
        if ($comment !== null && !$comment->isEmpty()) {
            $content .= str_replace('/**', '/*', $comment->toString()) . "\n";
        }

        /** @var ?Docblock $docblock */
        $docblock = $this->getConfig()->getHeaderDocblock();
        if ($docblock !== null && !$docblock->isEmpty()) {
            $content .= $docblock->toString() . "\n";
        }

        $content .= parent::generate($model);

        return $content;
    }
}
