<?php
namespace app\admin\controller;

use think\Exception;


class User extends Controller {

    
    public function login(){
    
        try{
            Server::login();
        }catch(Exception $e){

            $this->error($e->getMessage());
        }

        return $this->redirect('index/index');
        
    }


    public function logout(){

        Server::logout();
        return $this->redirect('user/login');
    }

    
}


