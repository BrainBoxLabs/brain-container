<?php
use BrainContainer\BrainCollection;

class BrainCollectionTest extends PHPUnit_Framework_TestCase{

	protected function tearDown(){
		Mockery::close();
	}

    protected function _getTestCollectionData(){
        return array(
            array('foo' => 'bar'),
            array('foo' => 'bar')
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
            $this->assertEquals('',$c->foo);
        }
    }

}