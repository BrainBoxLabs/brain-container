<?php
namespace BrainContainer;

class BrainContainer{

	protected $properties = array();
    protected $related = array();
    protected $fillable = array();
    protected $idAttr = 'id';
    protected $clone;

	public function __construct(){

	}

	public function __toString(){
		return '';
	}

	public function __get($key){

		if(!$this->has($key)){
			return '';
		}

		return $this->properties[$key];
	}

	public function __set($key,$value){
        if(is_object($key)){
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

	public function fill($properties,$unguard=false){
		foreach($properties as $property => $value){
            if(in_array($property,$this->fillable) || $unguard == true){
                $this->$property = $value;
            }
		}
	}

	public function toJson(){
		return json_encode($this->properties);
	}

	public function toArray(){
		return $this->properties;
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