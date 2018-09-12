<?php

use think\Session;


/**
 * 设置登录者信息
 * @param $user
 */
function set_login_user($user = ''){
    
    if(empty($user)){
        Session::delete('_login_user');
    }else{
        Session::set('_login_user', $user);
    }

}

/**
 * 获取登录者信息
 * @param $name
 * @return object
 */
function get_login_user($name = ''){
    
    $user = Session::get('_login_user');
    return  empty($name)? $user : $user[$name];
}

/**
 * 获取登录者名字
 * @param $name
 * @return object
 */
function get_login_name(){
    return  get_login_user('name');
    
}

/**
 * 获取登录者名字
 * @param $name
 * @return object
 */
function get_login_username(){
    return  get_login_user('username');
    
}

/**
 * 获取登录者uid
 * @param $name
 * @return object
 */
function get_login_uid(){
    return  get_login_user('user_id');
    
}




