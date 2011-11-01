<?php

class YamlFixtureTest extends SapphireTest {
	static $fixture_file = 'sapphire/tests/testing/YamlFixtureTest.yml';

	protected $extraDataObjects = array(
		'YamlFixtureTest_DataObject',
		'YamlFixtureTest_DataObjectRelation',
		'YamlFixtureTest_CircularOne',
		'YamlFixtureTest_CircularTwo'
	);
	
	function testSQLInsert() {
		$object1 = DataObject::get_by_id("YamlFixtureTest_DataObject", $this->idFromFixture("YamlFixtureTest_DataObject", "testobject1"));
		$this->assertTrue($object1->ManyMany()->Count() == 2, "Should be 2 items in this manymany relationship");
		$object2 = DataObject::get_by_id("YamlFixtureTest_DataObject", $this->idFromFixture("YamlFixtureTest_DataObject", "testobject2"));
		$this->assertTrue($object2->ManyMany()->Count() == 2, "Should be 2 items in this manymany relationship");
	}

	function testCircularReferences() {
		$circularOne = DataObject::get_by_id("YamlFixtureTest_CircularOne", $this->idFromFixture("YamlFixtureTest_CircularOne", "one"));
		$circularTwo = DataObject::get_by_id("YamlFixtureTest_CircularTwo", $this->idFromFixture("YamlFixtureTest_CircularTwo", "two"));

		$this->assertEquals($circularOne->HasOneID, $circularTwo->ID, "Circular references in has_one work");
		$this->assertEquals($circularOne->ID, $circularTwo->HasOneID, "Circular references in has_one work");

		$this->assertEquals($circularOne->HasMany()->First()->ID, $circularTwo->ID, "Circular references in has_many work");
		$this->assertEquals($circularOne->ID, $circularTwo->HasMany()->First()->ID, "Circular references in has_many work");

		$this->assertEquals($circularOne->ManyMany()->First()->ID, $circularTwo->ID, "Circular references in many_many work");
		$this->assertEquals($circularOne->ID, $circularTwo->ManyMany()->First()->ID, "Circular references in many_many work");
	}
}

class YamlFixtureTest_DataObject extends DataObject implements TestOnly {
	static $db = array(
		"Name" => "Varchar"
	);
	static $many_many = array(
		"ManyMany" => "YamlFixtureTest_DataObjectRelation"
	);
}

class YamlFixtureTest_DataObjectRelation extends DataObject implements TestOnly {
	static $db = array(
		"Name" => "Varchar"
	);
	static $belongs_many_many = array(
		"TestParent" => "YamlFixtureTest_DataObject"
	); 
}

class YamlFixtureTest_CircularOne extends DataObject implements TestOnly {
	static $db = array(
		"Name" => "Varchar"
	);
	static $has_one = array(
		"HasOne" => "YamlFixtureTest_CircularTwo",
	); 
	static $has_many = array(
		"HasMany" => "YamlFixtureTest_CircularTwo"
	);
	static $many_many = array(
		"ManyMany" => "YamlFixtureTest_CircularTwo"
	);
}

class YamlFixtureTest_CircularTwo extends DataObject implements TestOnly {
	static $db = array(
		"Name" => "Varchar"
	);
	static $has_one = array(
		"HasOne" => "YamlFixtureTest_CircularOne",
	); 
	static $has_many = array(
		"HasMany" => "YamlFixtureTest_CircularOne"
	);
	static $belongs_many_many = array(
		"ManyMany" => "YamlFixtureTest_CircularOne"
	);
}
