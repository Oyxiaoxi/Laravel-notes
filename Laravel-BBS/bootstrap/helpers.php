<?php
/*
 * @Author: Oyxiaoxi 
 * 自定义辅助类
 * route_class()
 */
function route_class() {
    return str_replace('.', '-', Route::currentRouteName());
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
}