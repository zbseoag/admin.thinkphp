<?php
namespace app\api\controller;


class Controller extends \app\common\Controller {


    protected function _initialize(){

        if(!$this->token()){

         //   exit('非法请求');
        }
    }


}
