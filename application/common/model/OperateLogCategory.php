<?php
namespace app\common\model;


use think\Exception;

class OperateLogCategory extends Model {
    
    

    public static function getList($field = '', $where = ''){
        
        if(!$field) $field = 'title';
        return self::where($where)->column($field, 'id');
        
    }
    
    
    public static function getRow($value, $field = ''){
        
        $value = trim($value);
        $where = is_numeric($value)? array('id' => $value) : array('name' => $value);
        if($field == '*' || stripos($field, ',')){
            return self::where($where)->field($field)->find();
        }else{
            return self::where($where)->value($field);
        }
        
    }
    
    public static function getName($value){
        
        return self::getRow($value, 'name');
    }
    
    public static function getId($value){
        
        return self::getRow($value, 'id');
    }
    
}