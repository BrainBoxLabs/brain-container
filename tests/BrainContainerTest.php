<?php
use BrainContainer\BrainContainer;

class BrainContainerTest extends PHPUnit_Framework_TestCase{

	protected function tearDown(){
		Mockery::close();
	}

	public function testContainerHasMagicProperties(){
		$container = new BrainContainer();
		$this->assertEquals('',$container->foo);
	}

	public function testContainerMagicPropertiesHaveValue(){
		$container = new BrainContainer();
		$container->foo = 'bar';
		$this->assertEquals('bar',$container->foo);
	}

	public function testFilledContainerHasPropertyValues(){
		$container = new BrainContainer();
		$container->fill(array(
			'foo' => 'bar'
		));
		$this->assertEquals('bar',$container->foo);
	}

	public function testMagicMethodCallReturnsAnotherBrainContainerInstance(){
		$container = new BrainContainer();
		$this->assertEquals('',$container->foo()->bar);
	}

	public function testToArrayReturnsRecursiveArray(){
		$container = new BrainContainer();
		$container->fill(array(
			'foo'=> (object)array(
					'bar' => 'yolo'
				)
		));

		$array = $container->toArray();

		$this->assertArrayHasKey('bar',$array['foo']);
	}

	public function testCanMakeANewContainerFromAnOldOne(){
		$container = new BrainContainer;
		$new_container = $container->make(array(
			'foo' => 'bar'
		));
		$this->assertEquals('bar',$new_container->foo);
	}

	public function testContainerIsACountableIterableCollection(){
		$container = new BrainContainer();
		$container->fill(array(
			array(
				'id' => '25',
				'name' => 'foo'
			),
			array(
				'id' => '30',
				'name' => 'foo'
			)
		),true);

		foreach($container as $c){
			$this->assertEquals('foo',$c->name);
		}

		$this->assertEquals(2,count($container));
	}

}