<?php
/*
 * @Author: Oyxiaoxi 
 * 自定义辅助类
 * route_class()
 */
function route_class() {
    return str_replace('.', '-', Route::currentRouteName());
}