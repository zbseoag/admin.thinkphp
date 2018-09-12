<?php
namespace app\common\service;

use think\Exception;
use app\common\model\User as Model;


class User {


    public static function register(){


    }


    public static function login($username, $password){

        if(empty($username)) Error::throw(Error::user_name_empty);

        if(empty($password)) Error::throw(Error::user_password_empty);

        $record = Model::getRow($username);

        if(empty($record)) Error::throw(Error::user_not_exist);

        if(!self::password($password, $record->password)) Error::throw(Error::user_password_error);

        unset($record->password);

        return $record;

	}
	
	
	public static function logout(){



	}


    public static function password($password, $hash = ''){

        if(func_num_args() == 1){

            return password_hash($password, PASSWORD_BCRYPT);
        }else{
            return password_verify($password, $hash);
        }


    }



}



