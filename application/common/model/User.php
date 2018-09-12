<?php
namespace app\common\model;


class User extends Model {


    const USERNAME_FIELD = 'username';
    const PASSWORD_FIELD = 'password';

    public $search = ['store_type_id', 'account' => 'account%'];
    
    
    public static function getStatusAttr($value = ''){
        
        $options = array(
            self::STATUS_ENABLE   => '已启用',
            self::STATUS_DISABLE  => '已禁用',
        );
        
        //获取所有选项
        if(func_num_args() == 0) return $options;
        return isset($options[$value])? $options[$value] : $value;
        
    }

    public static function getUserTypeAttr($value = ''){
        
        $options = array(
            '1' => '预付现结',
            '2' => '账期客户',
            '3' => '直销平台',
        );
        
        //获取所有选项
        if(func_num_args() == 0) return $options;
        return isset($options[$value])? $options[$value] : $value;
        
    }


    public static function condition($where){

        if(is_scalar($where)){
            $where = trim($where);
            $where = is_numeric($where)? array('id' => $where) : array('username' => $where);
        }

        return self::where($where);
    }


    public static function getRow($where, $field = ''){

        $query = self::condition($where);

        if(empty($field) || $field == '*' || stripos($field, ',')){
            return $query->field($field)->find();
        }else{
            return $query->value($field);
        }

    }


    /**
     * 获取简单列表
     * @param string $field
     * @return array
     */
    public static function getList($field = '', $where = ''){

        if(!$field) $field = 'username';
        return self::where($where)->column($field, 'user_id');

    }



    public static function getName($value){
    
        if(!is_numeric($value)) return trim($value);
        return self::getRow($value, 'user_name');
    }
    
    public static function getId($value){
        
        if(is_numeric($value)) return trim($value);
        return self::getRow($value, 'user_id');
    }
    




    public static function getUserTypeByCustomerId($value){
        
        return self::getRow(['customer_id' => $value], 'user_type');
    }

    
    
}