<?php declare(strict_types=1);

namespace Susina\Codegen\Generator;

use Susina\Codegen\Config\GeneratorConfig;
use Susina\Codegen\Model\AbstractModel;

/**
 * Code generator.
 *
 * Generates code for any generateable model
 *
 * @author Thomas Gossmann
 * @author Cristiano Cinotti
 */
class CodeGenerator
{
    const SORT_USESTATEMENTS_DEFAULT = 'default';

    const SORT_CONSTANTS_DEFAULT = 'default';

    const SORT_PROPERTIES_DEFAULT = 'default';

    const SORT_METHODS_DEFAULT = 'default';

    /** @var GeneratorConfig */
    protected $config;

    /** @var ModelGenerator */
    protected $generator;

    /**
     * @param mixed $config
     */
    public function __construct($config = null)
    {
        if (null === $config || is_array($config)) {
            $config = new GeneratorConfig($config);
        }

        if (!$config instanceof GeneratorConfig) {
            throw new \InvalidArgumentException('CodeGenerator constructor expects an array or a GeneratorConfig object.');
        }

        $this->config = $config;
        $this->generator = new ModelGenerator($this->config);
    }

    /**
     * Returns the used configuration.
     */
    public function getConfig(): GeneratorConfig
    {
        return $this->config;
    }

    /**
     * Generates code from a given model.
     *
     * @return string the generated code
     */
    public function generate(AbstractModel $model): string
    {
        return $this->generator->generate($model);
    }
}
