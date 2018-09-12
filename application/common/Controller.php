<?php
namespace app\common;

use think\exception\HttpException;

class Controller extends \think\Controller {


    //网关
    public $gateway = 'home/user/login';
    //无需登录
    public $allow = array();
    //用户id
    public $uid = 0;
    //用户名
    public $username = '';
    //用户
    public $user = array();


    protected function _initialize(){
        
        if(!empty($this->gateway)){
    
            $this->user = get_login_user();
            if(empty($this->user)){

                $method = strtolower($this->request->controller() . '/' . $this->request->action());

                if(substr_count($this->gateway, '/') >= 2){
                    $method = strtolower($this->request->module()) . '/' . $method;
                }

                //如果不在公共访问列表中
                if($method != $this->gateway && !in_array($method, $this->allow)){
                    $this->redirect($this->gateway);
                }
            }

            $this->uid = $this->user['id'];
            $this->uname= $this->user['username'];
            
        }

        $this->assign('uid', $this->uid);
        $this->assign('username', $this->username);

    }



    protected function token(){

        $token = $this->request->header('token');
        $timestamp  = $this->request->header('timestamp', 0);

        //10分钟之内有效
        if(time() - $timestamp > 600) return false;
        return ($token == $this->makeToken($timestamp, config('access_secret_key')))? true : false;

    }


    public function makeToken($timestamp, $secret_key = 'time'){

        $str = date('YmdH', $timestamp) . md5($secret_key) . date('HdmY', $timestamp);
        $str = implode('', array_unique(str_split($str)));

        return md5($str);

    }



}
