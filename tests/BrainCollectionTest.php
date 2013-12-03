<?php
use BrainContainer\BrainCollection;

class BrainCollectionTest extends PHPUnit_Framework_TestCase{

	protected function tearDown(){
		Mockery::close();
	}

    protected function _getTestCollectionData(){
        return array(
            array('id'=>1,'foo' => 'bar','order'=>2),
            array('id'=>2,'foo' => 'bar','order'=> 1)
        );
    }

    public function testCollectionIsCountable(){
        $collection = new BrainCollection();
        $this->assertEquals(0,count($collection));
    }

    public function testCollectionIsIteratable(){
        $collection = new BrainCollection();
        $collection->fill($this->_getTestCollectionData());
        foreach($collection as $c){
            $this->assertEquals('bar',$c->foo);
        }
    }

    public function testCollectionHasAFirst(){
        $collection = new BrainCollection();
        $collection->fill($this->_getTestCollectionData());
        $foo = $collection->first();
        $this->assertEquals('bar',$foo->foo);
    }

    public function testCollectionAllReturnsArray(){
        $collection = new BrainCollection();
        $this->assertEquals(0,count($collection->all()));
    }

    public function testCollectionToArray(){
        $collection = new BrainCollection();
        $collection->fill($this->_getTestCollectionData());
        $this->assertEquals(2,count($collection->toArray()));
    }

    public function testCollectionCanRemoveModels(){
        $collection = new BrainCollection();
        $collection->fill($this->_getTestCollectionData());
        $collection->remove(2);
        $this->assertEquals('',$collection->get(2)->foo);
    }

    public function testCollectionCanBeCleared(){
        $collection = new BrainCollection();
        $collection->fill($this->_getTestCollectionData());
        $collection->clear();
        $this->assertEquals('',$collection->get(2)->foo);
    }

    public function testCollectionCanHaveModelsPushedOntoIt(){
        $collection = new BrainCollection();
        $collection->fill($this->_getTestCollectionData());
        $collection->push(array(
            array(
                'id' => 3,
                'foo' => 'bar'
            )
        ));

        $this->assertEquals('bar',$collection->get(3)->foo);
    }

    public function testCollectionCanBeUpdated(){
        $collection = new BrainCollection();
        $collection->fill($this->_getTestCollectionData());
        $collection->push(array(
            array(
                'id' => 3,
                'foo' => 'bar'
            )
        ));

        $container = $collection->get(3);
        $container->foo = 'hello world';
        $collection->update($container);
        $container = $collection->get(3);

        $this->assertEquals('hello world',$container->foo);
    }

    public function testCollectionCanBeSorted(){
        $collection = new BrainCollection();
        $collection->fill($this->_getTestCollectionData());
        $collection->sort('order');
        $first = $collection->first();
        $this->assertEquals(2,$first->getID());
    }

    public function testCollectionIsFilterable(){
        $collection = new BrainCollection();
        $collection->fill($this->_getTestCollectionData());
        $collection->push(array(
            array(
                'id' => 3,
                'foo' => 'hello world'
            )
        ));

        $collection->filter('foo','bar');

        $this->assertEquals(2,count($collection->toArray()));
    }

    public function testCollectionCanFilterOneOut(){
        $collection = new BrainCollection();
        $collection->fill($this->_getTestCollectionData());
        $collection->push(array(
            array(
                'id' => 3,
                'foo' => 'hello world'
            )
        ));

        $container = $collection->filterOne('foo','hello world');

        $this->assertEquals('hello world',$container->foo);
    }

}