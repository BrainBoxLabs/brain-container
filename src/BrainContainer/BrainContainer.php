<?php
namespace BrainContainer;

class BrainContainer{

	protected $properties = array();
    protected $related = array();
    protected $allowable = array();
    protected $idAttr = 'id';
    protected $clone;

	public function __construct(){

	}

	public function __toString(){
		return '';
	}

    function __isset($key)
    {
        if(isset($this->properties[$key])){
            return true;
        }

        return false;
    }

	public function __get($key){

		if(!$this->has($key)){
			return '';
		}

		return $this->properties[$key];
	}

    public function __set($key,$value){
        if(is_object($value) || is_array($value)){
            if(is_array($value) || $value instanceof \stdClass){
                $value = (array)$value;
                $container = $this->_createNewContainer();
                $container->fill($value);
                $value = $container;
            }
            $this->related[$key] = $value;
        }else{
            $this->properties[$key] = $value;
        }
    }

	public function __call($method,$args){
		if(isset($this->related[$method])){
            return $this->related[$method];
        }

        return $this->_createNewContainer();
	}

    public function setIdAttr($id_string){
        $this->idAttr = $id_string;
    }

    public function getIDAttr(){
        return $this->idAttr;
    }

    public function getID(){
        $id_property = $this->getIDAttr();
        $id = $this->$id_property;

        if(empty($id)){
            return null;
        }

        return $id;
    }

    public function isNew(){
        if(is_null($this->getID())){
            return true;
        }
        return false;
    }

	/**
	 * @return BrainContainer
	 */
	protected function _createNewContainer(){
		$class = get_called_class();
		return new $class();
	}

    protected function _isAllowed($property,$allow_all=false){
        $allowable = false;

        if(count($this->allowable) < 1){
            $allowable = true;
        }elseif(in_array($property,$this->allowable)){
            $allowable = true;
        }

        if($allow_all){
            $allowable = true;
        }

        return $allowable;
    }

	public function fill($properties){
		foreach($properties as $property => $value){
            $this->$property = $value;
		}
	}

	public function toJson($allow_all=false){
		return json_encode($this->toArray($allow_all));
	}

	public function toArray($allow_all=false){
        $properties = array();
        foreach($this->properties as $property => $value){
            if($this->_isAllowed($property,$allow_all)){
                $properties[$property] = $value;
            }
        }
        return $properties;
	}

    public function escape($key){
        return strip_tags($this->$key);
    }

    public function has($key){
        if(isset($this->properties[$key])){
            return true;
        }
        return false;
    }

    public function remove($key){
        if($this->has($key)){
            unset($this->properties[$key]);
            return true;
        }

        return false;
    }

    public function clear(){
        $this->properties = array();
        $this->related = array();
    }

    public function makeNew(){
        return $this->_createNewContainer();
    }

    public function makeClone(){
        return clone($this);
    }

}