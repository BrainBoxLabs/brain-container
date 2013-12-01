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

	public function testFilledContainerHasNoPropertyValues(){
		$container = new BrainContainer();
		$container->fill(array(
			'foo' => 'bar'
		));
		$this->assertEquals('',$container->foo);
	}

    public function testToArrayReturnsArray(){
        $container = new BrainContainer();
        $container->fill(array(
            'foo'=>'bar'
        ),true);

        $array = $container->toArray();

        $this->assertArrayHasKey('foo',$array);
    }

	public function testMagicMethodCallReturnsAnotherBrainContainerInstance(){
		$container = new BrainContainer();
		$this->assertEquals('',$container->foo()->bar);
	}

    public function testGettingId(){
        $container = new BrainContainer();
        $container->id = 416;
        $this->assertEquals(416,$container->getID());
    }

    public function testChangingIdAttr(){
        $container = new BrainContainer();
        $container->setIdAttr('uuid');
        $container->uuid = 416;
        $this->assertEquals(416,$container->getID());
    }

    public function testContainerIsNew(){
        $container = new BrainContainer();
        $this->assertEquals(true,$container->isNew());
    }

    public function testContainerHasProperty(){
        $container = new BrainContainer();
        $container->foo = 'bar';
        $this->assertEquals(true,$container->has('foo'));
        $this->assertEquals(false,$container->has('something_that_doesnt_exist'));
    }

    public function testRemovePropertyFromContainer(){
        $container = new BrainContainer();
        $container->foo = 'bar';
        $container->remove('foo');
        $this->assertEquals('',$container->foo);
    }

    public function testClearingAContainer(){
        $container = new BrainContainer();
        $container->foo = 'bar';
        $container->clear();
        $this->assertEquals('',$container->foo);
    }

    public function testContainerCanBeCloned(){
        $container = new BrainContainer;
        $container->foo = 'bar';
        $container2 = $container->makeClone();
        $container3 = $container2->makeNew();

        $this->assertEquals('bar',$container2->foo);
        $this->assertEquals('',$container3->foo);
    }

}