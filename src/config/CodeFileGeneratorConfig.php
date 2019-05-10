<?php declare(strict_types=1);

namespace cristianoc72\codegen\config;

use gossi\docblock\Docblock;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Configuration for code file generation
 *
 * @author Thomas Gossmann
 */
class CodeFileGeneratorConfig extends CodeGeneratorConfig
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'headerComment' => null,
            'headerDocblock' => null
        ]);
        
        $resolver->setAllowedTypes('headerComment', ['null', 'string', 'gossi\\docblock\\Docblock']);
        $resolver->setAllowedTypes('headerDocblock', ['null', 'string', 'gossi\\docblock\\Docblock']);

        $resolver->setNormalizer('headerComment', function (Options $options, $value) {
            return $this->toDocblock($value);
        });
        $resolver->setNormalizer('headerDocblock', function (Options $options, $value) {
            return $this->toDocblock($value);
        });
    }
    
    /**
     *
     * @param mixed $value
     * @return Docblock|null
     */
    private function toDocblock($value): ?Docblock
    {
        if (is_string($value)) {
            $value = Docblock::create()->setLongDescription($value);
        }
        
        return $value;
    }

    /**
     * Returns the file header comment
     *
     * @return null|Docblock the header comment
     */
    public function getHeaderComment(): ?Docblock
    {
        return $this->options['headerComment'];
    }

    /**
     * Sets the file header comment
     *
     * @param string $comment the header comment
     * @return $this
     */
    public function setHeaderComment(string $comment): self
    {
        $this->options['headerComment'] = new Docblock($comment);
        return $this;
    }

    /**
     * Returns the file header docblock
     *
     * @return Docblock the docblock
     */
    public function getHeaderDocblock(): ?Docblock
    {
        return $this->options['headerDocblock'];
    }

    /**
     * Sets the file header docblock
     *
     * @param Docblock $docblock the docblock
     * @return $this
     */
    public function setHeaderDocblock(Docblock $docblock): self
    {
        $this->options['headerDocblock'] = $docblock;
        return $this;
    }
}
