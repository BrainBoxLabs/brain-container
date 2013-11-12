<?php
use BrainContainer\BrainCollection;

class BrainCollectionTest extends PHPUnit_Framework_TestCase{

	protected function tearDown(){
		Mockery::close();
	}

	protected function getTestData(){
		return array(
			array(
				'foo' => 'bar'
			),
			array(
				'hello' => 'world'
			)
		);
	}

	protected function getStoredTestData(){
		return array(
			array(
				'id' => 416,
				'foo' => 'bar'
			),
			array(
				'id' => 77,
				'hello' => 'world'
			)
		);
	}

	protected function getStoredTestDataWithADifferentKey(){
		return array(
			array(
				'_field_id' => 416,
				'foo' => 'bar'
			),
			array(
				'_field_id' => 77,
				'hello' => 'world'
			)
		);
	}

	public function testCollectionCanBeCounted(){
		$collection = new BrainCollection();
		$this->assertEquals(0,count($collection));
	}

	public function testCollectionCanBeCountedWithData(){
		$collection = new BrainCollection();
		$collection->fill($this->getTestData());

		$this->assertEquals(2,count($collection));
	}

	public function testCollectionIsIterable(){
		$collection = new BrainCollection();
		$collection->fill($this->getTestData());
		foreach($collection as $container){
			$this->assertEquals('',$container->some_property_that_doesnt_exist);
		}
	}

	public function testGrabbingAContainerOffTheCollection(){
		$collection = new BrainCollection();
		$collection->fill($this->getStoredTestData());
		$container = $collection->get(416);
		$this->assertEquals('bar',$container->foo);
	}

	public function testSettingAnIdFieldToCheckForAndSwapPropertyKeysWith(){
		$collection = new BrainCollection();
		$collection->setID('_field_id');
		$collection->fill($this->getStoredTestDataWithADifferentKey());
		$container = $collection->get(416);
		$this->assertEquals('bar',$container->foo);
	}

	public function testGettingTheFirstItemOffACollection(){
		$collection = new BrainCollection();
		$collection->fill($this->getTestData());
		$container = $collection->first();
		$this->assertEquals('bar',$container->foo);
	}

}