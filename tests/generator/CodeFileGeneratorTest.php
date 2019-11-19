<?php declare(strict_types=1);

namespace cristianoc72\codegen\tests\generator;

use cristianoc72\codegen\config\GeneratorConfig;
use cristianoc72\codegen\generator\CodeFileGenerator;
use cristianoc72\codegen\model\PhpClass;
use cristianoc72\codegen\model\PhpConstant;
use cristianoc72\codegen\model\PhpFunction;
use cristianoc72\codegen\model\PhpMethod;
use cristianoc72\codegen\model\PhpParameter;
use cristianoc72\codegen\model\PhpProperty;
use gossi\docblock\Docblock;

/**
 * @group generator
 */
class CodeFileGeneratorTest extends GeneratorTestCase
{
    public function testStrictTypesDeclaration(): void
    {
        $expected = '<?php declare(strict_types=1);

/**
 * @param $a
 */
function fn($a)
{
}
';
        $fn = PhpFunction::create('fn')->addParameter(PhpParameter::create('a'));

        $codegen = new CodeFileGenerator(['generateEmptyDocblock' => false]);
        $code = $codegen->generate($fn);

        $this->assertEquals($expected, $code);
    }

    public function testExpression(): void
    {
        $class = new PhpClass('ClassWithExpression');
        $class
            ->setConstant(PhpConstant::create('FOO', 'BAR'))
            ->setProperty(
                PhpProperty::create('bembel')
                    ->setExpression("['ebbelwoi' => 'is eh besser', 'als wie' => 'bier']")
            )
            ->setMethod(PhpMethod::create('getValue')
                ->addParameter(
                    PhpParameter::create('arr')
                        ->setExpression('[self::FOO => \'baz\']')
                        ->setType('array')
                ));

        $codegen = new CodeFileGenerator();
        $code = $codegen->generate($class);

        $this->assertEquals($this->getGeneratedContent('ClassWithExpression.php'), $code);
    }

    public function testDocblocks(): void
    {
        $generator = new CodeFileGenerator([
            'headerComment'         => 'hui buuh',
            'headerDocblock'        => 'woop',
            'generateEmptyDocblock' => true,
        ]);

        $class = new PhpClass('Dummy');
        $code = $generator->generate($class);

        $this->assertEquals($this->getGeneratedContent('Dummy.php'), $code);
    }

    public function testEntity(): void
    {
        $class = $this->createEntity();

        $generator = new CodeFileGenerator(['generateEmptyDocblock' => false]);
        $code = $generator->generate($class);

        $this->assertEquals($this->getGeneratedContent('Entity.php'), $code);
    }

    public function testConfig(): void
    {
        $generator = new CodeFileGenerator(null);
        $this->assertTrue($generator->getConfig() instanceof GeneratorConfig);

        $config = new GeneratorConfig();
        $generator = new CodeFileGenerator($config);
        $this->assertSame($config, $generator->getConfig());
    }

    protected function createEntity(): PhpClass
    {
        $classDoc = new Docblock('/**
 * Doc Comment.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */');

        $propDoc = new Docblock('/**
 * @var integer
 */');
        $class = new PhpClass();
        $class->setQualifiedName('cristianoc72\codegen\tests\fixtures\Entity')
            ->setAbstract(true)
            ->setDocblock($classDoc)
            ->setDescription($classDoc->getShortDescription())
            ->setLongDescription($classDoc->getLongDescription())
            ->setProperty(PhpProperty::create('id')
                ->setVisibility('private')
                ->setDocblock($propDoc)
                ->setType('integer')
                ->setDescription($propDoc->getShortDescription()))
            ->setProperty(PhpProperty::create('enabled')
                ->setVisibility('private')
                ->setValue(false));

        $methodDoc = new Docblock('/**
 * Another doc comment.
 *
 * @param $a
 * @param array $b
 * @param \stdClass $c
 * @param string $d
 * @param callable $e
 */');
        $method = PhpMethod::create('__construct')
            ->setFinal(true)
            ->addParameter(PhpParameter::create('a'))
            ->addParameter(PhpParameter::create()
                ->setName('b')
                ->setType('array')
                ->setPassedByReference(true))
            ->addParameter(PhpParameter::create()
                ->setName('c')
                ->setType('\\stdClass'))
            ->addParameter(PhpParameter::create()
                ->setName('d')
                ->setType('string')
                ->setValue('foo'))
            ->addParameter(PhpParameter::create()
                ->setName('e')
                ->setType('callable'))
            ->setDocblock($methodDoc)
            ->setDescription($methodDoc->getShortDescription())
            ->setLongDescription($methodDoc->getLongDescription());

        $class->setMethod($method);
        $class->setMethod(PhpMethod::create('foo')->setAbstract(true)->setVisibility('protected'));
        $class->setMethod(PhpMethod::create('bar')->setStatic(true)->setVisibility('private'));

        return $class;
    }
}
