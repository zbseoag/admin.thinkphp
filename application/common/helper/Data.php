<?php
namespace app\common\helper;
use think\Exception;

class Data {
    
    
    /**
     * 取得值
     * @param fixed $variable
     * @param string $default
     * @return fixed
     */
    public static function get(&$var, $default = ''){
        
        return (isset($var) && !empty($var))? $var: $default;
    }
    
    /**
     * 判断变量是否为空白字符
     * @param fixed $data
     * @param array $options
     * @throws Exception
     * @return boolean
     */
    public static function space(&$data, $options = [], $allow = []){
        
        if(is_string($options)){
            $allow = $options; $options = [];
        }
        
        if(!is_array($data)){
            return (!isset($data) || trim($data) === '')? true : false;
        }
        
        if(!empty($allow)){
            if(is_string($allow)) $allow = explode(',', str_replace(' ', '', $allow));
        }
        
        if(empty($options)){
            
            foreach($data as $key => $var){
                if(in_array($key, $allow)) continue;
                if(!isset($var) || trim($var) === '') return $key;
            }
            
        }else{
            
            foreach($options as $key => $message){
                if(!isset($data[$key]) || trim($data[$key]) === ''){
                    throw new Exception($message . '不能为空');
                }
            }
        }
        return false;
        
    }
    


    public static function combine($keys, $values = null){
    
        if($values == null){
            $values = $keys;
            $keys = array_values($keys);
        }
        return array_combine($keys, $values);
    
    }
    
    
    /**
     * 替换空白字符
     * @param $string
     * @param string $replace
     * @return string
     */
    public static function replace($content, $pattern = '', $replace = ''){
        
        if(empty($pattern)) $pattern = '/(\s|&nbsp;|　|\xc2\xa0)+/';
        
        if(is_string($content)){
            $content = trim(preg_replace($pattern, $replace, $content), $replace);
        }else{
            foreach($content as $key => $string){
                $content[$key] = trim(preg_replace($pattern, $replace, $string), $replace);
            }
        }
        return $content;
    }
    
    /**
     * 替换空白字符
     * @param $string
     * @param string $replace
     * @return string
     */
    public static function trim($content, $charlist = " \t\n\r\0\x0B"){
        
        if(is_string($content)){
            $content = trim($content, $charlist);
        }else{
            foreach($content as $key => $string){
                $content[$key] = trim($string, $charlist);
            }
        }
        return $content;
        
    }
    
    /**
     * 数组键名转换
     * @param $param
     * @return mixed
     */
    public function convert($param, $convert = array()){
        
        foreach($convert as $key => $field){
            
            if(isset($param[$key])){
                if(!empty($field)) $param[$field] = $param[$key];
                unset($param[$key]);
            }
        }
        
        return $param;
        
    }
    

    
    
}
