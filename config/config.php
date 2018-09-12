<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return array_merge([


    // 默认模块名
    'default_module'  => 'index',

    // 数据集返回类型
    'resultset_type'  => 'collection',

    // 扩展函数文件
    //'extra_file_list' => [APP_PATH . 'common/Code' . EXT],


], defined('ENVIRONMENT')? require ENVIRONMENT . '.config.php' : []);


