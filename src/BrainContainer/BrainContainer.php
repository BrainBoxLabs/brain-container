<?php
namespace BrainContainer;

class BrainContainer implements \IteratorAggregate,\Countable{

	protected $properties;
	protected $container_class_name;

	public function __construct($container=null){

		if(!is_object($container)){
			$this->container_class_name = 'BrainContainer\BrainContainer';
		}else{
			$this->container_class_name = get_class($container);
		}

	}

	public function count(){
		return $this->properties->count();
	}

	public function getIterator(){
		return $this->properties->getIterator();
	}

	public function __toString(){
		return '';
	}

	public function __get($key){

		if(!isset($this->properties[$key])){
			return '';
		}

		return $this->properties[$key];
	}

	public function __set($key,$value){
		$this->properties[$key] = $value;
	}

	public function __call($method,$args){
		$container = $this->_createNewContainer();
		$container->fill($this->$method);

		return $container;
	}

	/**
	 * @return BrainContainer
	 */
	protected function _createNewContainer(){
		$class = $this->container_class_name;
		return new $class();
	}

	public function fill($properties,$is_collection=false){
		if(!$is_collection){
			$this->properties = (array)$properties;
		}else{
			$this->properties = new BrainCollection($this);
			$this->properties->fill((array)$properties);
		}
	}

	public function make($properties=array(),$is_collection=false){
		$container = $this->_createNewContainer();
		$container->fill($properties,$is_collection);
		return $container;
	}

	public function toJson(){
		return json_encode($this->properties);
	}

	public function toArray(){
		return json_decode($this->toJson(),true);
	}

}