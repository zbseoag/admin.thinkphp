<?php
namespace app\common;

class Object {

    public static $instance = array();
    public $error = '';
    public $options = null;
    
    public function __construct($options = array()){
    
        if(is_string($options)) $options = json_decode($options);
        if(is_array($options)) $options =  (object) $options;
    
        $options = clone $options;
        foreach($options as $name => $item){
            if(property_exists($this, $name)){
                $this->$name = $item;
                unset($options->$name);
            }
        }
        
        if(!is_null($options)) $this->options = $options;
        
        $this->initialize();
    }
    
    
    public static function instance($options = array()){
        
        $class = str_replace('\\', '_', get_called_class());
        if(!isset(static::$instance[$class])){
            static::$instance[$class] = new static($options);
        }
        return static::$instance[$class];
    }
    
    public function initialize(){}
    
    
    /**
     * 取得值
     * @param $variable 变量
     * @param string $default 默认值
     * @param array $map 键值映射
     * @return mixed|string
     */
    public static function value(&$variable, $default = '', $map = array()){
        
        $result = (isset($variable) && $variable !== '')? $variable : $default;
        return (!empty($map) && isset($map[$result]))? $map[$result] : $result;
        
    }

    
    public static function equal($variate, $value){
        
        return ($variate === $value || $variate === strval($value))? true : false;
    }
    
    public static function zero($variate){
        
        return static::equal($variate, 0);
    }
    
    
    public function error($message = '', $code = ''){
        
        if(func_num_args() == 0) return $this->error;
        $this->error = $message . "( $code )";
        
    }
    
    
    /**
     * 移出数组元素
     * @param $data
     * @param $key
     * @param string $dafault
     * @return string
     */
    public static function shift(&$data, $key, $dafault = ''){
        
        if(is_array($key)){
            foreach($key as $index){
                $result[$index] = $data[$index];
                unset($data[$index]);
            }
        }else{
            $result = isset($data[$key])? $data[$key] : $dafault;
            unset($data[$key]);
        }
        
        return $result;
    }


    public static function toArray($object){

        if(is_object($object)){
            return json_decode(json_encode($object), true);
        }else if(is_string($object)){
            return json_decode($object, true);
        }

        return $object;
    }


    public static function toObject($array){

        if(is_array($array)){
            return json_decode(json_encode($array));
        }else if(is_string($array)){
            return json_decode($array);
        }

        return $array;
    }
    
}
