<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
return [

    // 应用调试模式
    'app_debug'              => true,
    // 显示错误信息
    'show_error_msg'         => true,
    // 应用Trace
    'app_trace'              => true,

    // 显示错误信息
    'show_error_msg'        =>  true,

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => ['error', 'sql', 'notice', 'debug'],
    ],

    'trace' => [
        'type' => 'Console',
    ],
    
];

