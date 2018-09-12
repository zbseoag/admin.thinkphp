<?php
namespace app\common\service;


class Error {

    const user_not_exist                = array('code' => 401001,    'msg' => '用户不存在');
    const user_password_error           = array('code' => 401002,    'msg' => '密码错误');
    const not_configured_error_msg      = array('code' => 50001,     'msg' => '未配置的错误消息');


    public static function get($key = ''){

        if(empty($key)) return self::$config;
        if(!isset(self::$config[$key])) $key = 'not_configured_error_msg';

        return (object) self::$config[$key];

    }


    public static function set($key, $error){

        self::$config[$key] = $error;
        return (object) self::$config[$key];

    }



    public static function throw($key, $detail = '', $param = array()){

        if(!is_scalar($detail)) $detail = json_encode($detail, JSON_UNESCAPED_UNICODE);
        $error = self::get($key);

        $data = array(
            
        );
        throw new \think\Exception($error->msg . $detail, $error->code);


    }


}