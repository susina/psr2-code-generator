<?php declare(strict_types=1);

namespace cristianoc72\codegen\generator;

use cristianoc72\codegen\config\CodeFileGeneratorConfig;
use cristianoc72\codegen\config\CodeGeneratorConfig;
use cristianoc72\codegen\model\AbstractModel;
use cristianoc72\codegen\model\GenerateableInterface;
use phootwork\lang\Text;

/**
 * Code file generator.
 *
 * Generates code for a model and puts it into a file with `<?php` statements. Can also
 * generate header comments.
 *
 * @author Thomas Gossmann
 */
class CodeFileGenerator extends CodeGenerator
{

    /**
     * Creates a new CodeFileGenerator
     *
     * @see https://php-code-generator.readthedocs.org/en/latest/generator.html
     * @param CodeFileGeneratorConfig|array $config
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
    }
    
    protected function configure($config = null)
    {
        if (is_array($config)) {
            $this->config = new CodeFileGeneratorConfig($config);
        } elseif ($config instanceof CodeFileGeneratorConfig) {
            $this->config = $config;
        } else {
            $this->config = new CodeFileGeneratorConfig();
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return CodeGeneratorConfig
     */
    public function getConfig(): CodeGeneratorConfig
    {
        return $this->config;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(AbstractModel $model): string
    {
        $content = "<?php declare(strict_types=1);\n\n";

        $comment = $this->config->getHeaderComment();
        if ($comment !== null && !$comment->isEmpty()) {
            $content .= str_replace('/**', '/*', $comment->toString()) . "\n";
        }

        $docblock = $this->config->getHeaderDocblock();
        if ($docblock !== null && !$docblock->isEmpty()) {
            $content .= $docblock->toString() . "\n";
        }

        $content .= parent::generate($model);

        if (!Text::create($content)->endsWith("\n")) {
            $content .= "\n";
        }

        return $content;
    }
}
