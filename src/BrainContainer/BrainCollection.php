<?php
namespace BrainContainer;

class BrainCollection implements \IteratorAggregate,\Countable{

	protected $id_field = 'id';
	protected $properties;
	protected $iterator_array=array();

	protected $container_class_name;

	public function __construct($container=null){

		if(!is_object($container)){
			$this->container_class_name = 'BrainContainer\BrainContainer';
		}else{
			$this->container_class_name = get_class($container);
		}

	}

	/**
	 * @return BrainContainer
	 */
	protected function _createNewContainer(){
		$class = $this->container_class_name;
		return new $class();
	}

	public function count(){
		return count($this->iterator_array);
	}

	public function getIterator(){
		return new \ArrayIterator($this->iterator_array);
	}

	public function get($id){
		$container = $this->_createNewContainer();

		if(isset($this->properties[$id])){
			$container->fill($this->properties[$id]);
		}

		return $container;
	}

	public function setID($property_name){
		$this->id_field = $property_name;
	}

	public function first(){
		reset($this->properties);
		$container = $this->_createNewContainer();
		$container->fill(current($this->properties));
		return $container;
	}

	public function fill($properties=array()){
		$this->properties = (array)$properties;
		$this->_rebuildPropertiesWithIdField();

		$iterator_array = array();
		foreach($this->properties as $key => $value){
			$container = $this->_createNewContainer();
			$container->fill($value);
			$iterator_array[$key] = $container;
		}

		$this->iterator_array = $iterator_array;

	}

	protected function _rebuildPropertiesWithIdField(){
		$properties = $this->properties;

		$new_properties = array();
		foreach($properties as $property){
			if(is_array($property) && array_key_exists($this->id_field,$property)){
				$new_properties[$property[$this->id_field]] = $property;
			}
		}

		if(count($new_properties) > 0){
			$this->properties = $new_properties;
		}
	}

}