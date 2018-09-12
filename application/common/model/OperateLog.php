<?php
namespace app\common\model;


use think\Exception;

class OperateLog extends Model {
    
    protected $updateTime = '';
    
    
    public static function getCategoryIdAttr($value = ''){
        
        return  OperateLogCategory::where('id', $value)->value('title');
    }
    
    
    public static function build($id, $source, $update, $name){
        
        $record = OperateLogCategory::where('name', $name)->find();
        if(empty($record)) throw new Exception('操作日志字段表查询表名: '. $name .' 为空');


        $field = json_decode($record->getData('field'), true);
        $intersect = array_intersect_key($source, $update);
        $result = array();
        foreach($intersect as $key => $value){

            if(!isset($field[$key]) || $update[$key] == $value) continue;
            $result[] = "{$field[$key]}从\"$value\"修改为\"{$update[$key]}\"";
        }
        
        return self::add($record->id, $id, $result);
        
    }
    
    
    public static function add($category_id, $origin_id, $info){
        
        if(empty($info)) return true;
        
        if(is_array($info)) $info = implode('，', $info);
        $data = array(
            'info' => $info,
            'category_id' => $category_id,
            'origin_id' => $origin_id,
            'creator_name' => get_login_name(),
        );
        
        return self::create($data);
        
    }
    
    
    
}