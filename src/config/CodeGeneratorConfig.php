<?php declare(strict_types=1);

namespace cristianoc72\codegen\config;

use cristianoc72\codegen\generator\CodeGenerator;
use phootwork\lang\Comparator;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Configuration for code generation
 *
 * @author Thomas Gossmann
 */
class CodeGeneratorConfig
{
    protected $options;

    /**
     * Creates a new configuration for code generator
     *
     * @see https://cristianoc72.github.io/psr2-code-generator/generator.html
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }
    
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'generateEmptyDocblock' => false,
            'enableSorting' => true,
            'useStatementSorting' => CodeGenerator::SORT_USESTATEMENTS_DEFAULT,
            'constantSorting' => CodeGenerator::SORT_CONSTANTS_DEFAULT,
            'propertySorting' => CodeGenerator::SORT_PROPERTIES_DEFAULT,
            'methodSorting' => CodeGenerator::SORT_METHODS_DEFAULT
        ]);
        
        $resolver->setAllowedTypes('generateEmptyDocblock', 'bool');
        $resolver->setAllowedTypes('enableSorting', 'bool');
        $resolver->setAllowedTypes('useStatementSorting', ['bool', 'string', '\Closure', 'phootwork\lang\Comparator']);
        $resolver->setAllowedTypes('constantSorting', ['bool', 'string', '\Closure', 'phootwork\lang\Comparator']);
        $resolver->setAllowedTypes('propertySorting', ['bool', 'string', '\Closure', 'phootwork\lang\Comparator']);
        $resolver->setAllowedTypes('methodSorting', ['bool', 'string', '\Closure', 'phootwork\lang\Comparator']);
    }

    /**
     * Returns whether empty docblocks are generated
     *
     * @return bool `true` if they will be generated and `false` if not
     */
    public function getGenerateEmptyDocblock(): bool
    {
        return $this->options['generateEmptyDocblock'];
    }

    /**
     * Sets whether empty docblocks are generated
     *
     * @param bool $generate `true` if they will be generated and `false` if not
     * @return $this
     */
    public function setGenerateEmptyDocblock(bool $generate): self
    {
        $this->options['generateEmptyDocblock'] = $generate;

        return $this;
    }

    /**
     * Returns whether sorting is enabled
     *
     * @return bool `true` if it is enabled and `false` if not
     */
    public function isSortingEnabled(): bool
    {
        return $this->options['enableSorting'];
    }
    
    /**
     * Returns the use statement sorting
     *
     * @return string|bool|Comparator|\Closure
     */
    public function getUseStatementSorting()
    {
        return $this->options['useStatementSorting'];
    }

    /**
     * Returns the constant sorting
     *
     * @return string|bool|Comparator|\Closure
     */
    public function getConstantSorting()
    {
        return $this->options['constantSorting'];
    }
    
    /**
     * Returns the property sorting
     *
     * @return string|bool|Comparator|\Closure
     */
    public function getPropertySorting()
    {
        return $this->options['propertySorting'];
    }
    
    /**
     * Returns the method sorting
     *
     * @return string|bool|Comparator|\Closure
     */
    public function getMethodSorting()
    {
        return $this->options['methodSorting'];
    }
    
    /**
     * Returns whether sorting is enabled
     *
     * @param $enabled bool `true` if it is enabled and `false` if not
     * @return $this
     */
    public function setSortingEnabled(bool $enabled): self
    {
        $this->options['enableSorting'] = $enabled;
        return $this;
    }
    
    /**
     * Returns the use statement sorting
     *
     * @param $sorting string|bool|Comparator|\Closure
     * @return $this
     */
    public function setUseStatementSorting(bool $sorting): self
    {
        $this->options['useStatementSorting'] = $sorting;
        return $this;
    }
    
    /**
     * Returns the constant sorting
     *
     * @param $sorting string|bool|Comparator|\Closure
     * @return $this
     */
    public function setConstantSorting($sorting): self
    {
        $this->options['constantSorting'] = $sorting;
        return $this;
    }
    
    /**
     * Returns the property sorting
     *
     * @param $sorting string|bool|Comparator|\Closure
     * @return $this
     */
    public function setPropertySorting($sorting): self
    {
        $this->options['propertySorting'] = $sorting;
        return $this;
    }
    
    /**
     * Returns the method sorting
     *
     * @param $sorting string|bool|Comparator|\Closure
     * @return $this
     */
    public function setMethodSorting($sorting): self
    {
        $this->options['methodSorting'] = $sorting;
        return $this;
    }
}
