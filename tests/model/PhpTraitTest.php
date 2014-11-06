<?php
namespace gossi\codegen\tests\model;

use gossi\codegen\model\PhpTrait;
use gossi\codegen\generator\CodeGenerator;

class PhpTraitTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		// they are not explicitely instantiated through new WhatEver(); and such not
		// required through composer's autoload
		require_once __DIR__ . '/../fixture/DummyTrait.php';
	}
	
	public function testFromReflection() {
		$trait = new PhpTrait('DummyTrait');
		$trait->setNamespace('gossi\codegen\tests\fixture');
		$trait->setDescription('Dummy docblock');
		$trait->generateDocblock();
		$this->assertEquals($trait, PhpTrait::fromReflection(new \ReflectionClass('gossi\codegen\tests\fixture\DummyTrait')));
	}
	
	public function testSignature() {
		$expected = 'trait MyTrait {'."\n". '}';
		
		$trait = PhpTrait::create('MyTrait');
		
		$codegen = new CodeGenerator(['generateDocblock' => false, 'generateEmptyDocblock' => false]);
		$code = $codegen->generate($trait);
		
		$this->assertEquals($expected, $code);
	}
	
	
}
