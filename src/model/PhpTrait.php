<?php
namespace cristianoc72\codegen\model;

use cristianoc72\codegen\model\parts\PropertiesPart;
use cristianoc72\codegen\model\parts\TraitsPart;
use cristianoc72\codegen\parser\FileParser;
use cristianoc72\codegen\parser\visitor\ConstantParserVisitor;
use cristianoc72\codegen\parser\visitor\MethodParserVisitor;
use cristianoc72\codegen\parser\visitor\PropertyParserVisitor;
use cristianoc72\codegen\parser\visitor\TraitParserVisitor;

/**
 * Represents a PHP trait.
 *
 * @author Thomas Gossmann
 */
class PhpTrait extends AbstractPhpStruct implements GenerateableInterface, TraitsInterface, PropertiesInterface
{
    use PropertiesPart;
    use TraitsPart;

    /**
     * Creates a PHP trait from a file
     *
     * @param string $filename
     * @return PhpTrait
     */
    public static function fromFile($filename)
    {
        $trait = new PhpTrait();
        $parser = new FileParser($filename);
        $parser->addVisitor(new TraitParserVisitor($trait));
        $parser->addVisitor(new MethodParserVisitor($trait));
        $parser->addVisitor(new ConstantParserVisitor($trait));
        $parser->addVisitor(new PropertyParserVisitor($trait));
        $parser->parse();
        
        return $trait;
    }

    /**
     * Creates a new PHP trait
     *
     * @param string $name qualified name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->initProperties();
    }

    /**
     * @inheritDoc
     */
    public function generateDocblock()
    {
        parent::generateDocblock();

        foreach ($this->properties as $prop) {
            $prop->generateDocblock();
        }
    }
}
