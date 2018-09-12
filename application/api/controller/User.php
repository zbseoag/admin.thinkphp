<?php
namespace app\api\controller;

use app\common\service\User as Service;

use think\Exception;

class User extends Controller {


    public function login(){

        $username = $this->request->get('username');
        $password = $this->request->get('password');

        try{
            $record = Service::login($username, $password);

            return $record;

        }catch(Exception $e){

            return $e->getMessage();
        }


    }


    public function logout(){


        try{

            Service::logout();

        }catch(Exception $e){


        }

    }

    

}
