<?php
namespace BrainContainer;

class BrainCollection implements \IteratorAggregate,\Countable{

    protected $Container;
    protected $models = array();

	public function __construct(BrainContainer $Container=null){

        if(is_null($Container)){
            $this->Container = new BrainContainer();
        }else{
            $this->Container = $Container;
        }

	}

	public function count(){
		return count($this->models);
	}

	public function getIterator(){
		return new \ArrayIterator($this->models);
	}

    /**
     * @return BrainContainer
     */
	public function first(){
		reset($this->models);
		return current($this->models);
	}

    public function all(){
        return $this->models;
    }

    public function toArray(){
        $arrays = array();
        foreach($this->models as $i => $model){
            $arrays[$i] = $model->toArray();
        }
        return $arrays;
    }

    public function toJSON(){
        $arrays = array();
        foreach($this->models as $i => $model){
            $arrays[$i] = $model->toJSON();
        }
        return $arrays;
    }

    protected function _createIdKeys($models){
        $collection = array();
        foreach($models as $model){
            if(!$model instanceof BrainContainer){
                $container = $this->Container->makeNew();
                $container->fill($model);
                $model = $container;
            }

            if(!is_null($model->getID())){
                $collection[$model->getID()] = $model;
            }else{
                $collection[] = $model;
            }
        }
        return $collection;
    }

    public function fill($models,$push=false){
        if(!is_array($models)){
            $models = array($models);
        }

        if(!$push){
            $this->models = $this->_createIdKeys($models);
        }else{
            $this->models = ($this->models+$this->_createIdKeys($models));
        }


        return $this->models;

    }

    public function remove($id){
        if(isset($this->models[$id])){
            unset($this->models[$id]);
            return true;
        }
        return false;
    }

    /**
     * @return BrainContainer
     */
    public function get($id){

        if(isset($this->models[$id])){
            return $this->models[$id];
        }
        return $this->Container->makeNew();
    }

    public function clear(){
        $this->models = array();
    }

    public function push($models){
        return $this->fill($models,true);
    }

    public function update(BrainContainer $Container){
        $id=$Container->getID();
        if(isset($this->models[$id])){
            $this->models[$id] = $Container;
            return true;
        }

        return false;
    }

    public function sort($by_field){
        return uasort($this->models,function($a,$b) use($by_field){
            return strcmp($a->$by_field,$b->$by_field);
        });
    }

    public function filter($field,$value){
        return $this->models = array_filter($this->models,function($model) use($field,$value){
            if($model->$field == $value){
                return true;
            }
            return false;
        });
    }

    /**
     * @return BrainContainer
     */
    public function filterOne($field,$value){
        $match = null;

        foreach($this->models as $m){
            if($m->$field == $value){
                $match = $m;
                break;
            }
        }

        return $match;

    }

}