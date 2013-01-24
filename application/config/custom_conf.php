<?php

/**
 * 错误码定义
 * 10000—用户相关
 * 20000—图片相关
 * 30000—商品相关
 * 40000—商品咨询相关
 * 50000—私信相关
 */
$config['error_def'] = array(

    "88888" => array(
       "error_code"=>"88888",
       "error_desc"=>"Operation successful",
    ),
    "99999" => array(
        "error_code"=>"99999",
        "error_desc"=>"Unknown error",
    ),
    "10001" => array(
        "error_code"=>"10001",
        "error_desc"=>"Username or password is wrong",
    ),
    "10002" => array(
        "error_code"=>"10002",
        "error_desc"=>"User name must be 4-20 length",
    ),
    "10003" => array(
        "error_code"=>"10003",
        "error_desc"=>"User name not allow phone no.",
    ),
    "10004" => array(
        "error_code"=>"10004",
        "error_desc"=>"Password must be 6-18 length",
    ),
    "10005" => array(
        "error_code"=>"10005",
        "error_desc"=>"Login is expired",
    ),
    "10006" => array(
        "error_code"=>"10006",
        "error_desc"=>"Username has existed",
    ),
    "10009" => array(
    		"error_code"=>"10009",
    		"error_desc"=>"Username is not a correct pattern",
    ),
    "10010" => array(
    		"error_code"=>"10010",
    		"error_desc"=>"PassWord is not a correct pattern",
    ),
    "10011" => array(
    		"error_code"=>"10010",
    		"error_desc"=>"the Username is not allowed",
    ),
    "20001" => array(
        "error_code"=>"20001",
        "error_desc"=>"Can’t create image upload path",
    ),
    "20002" => array(
        "error_code"=>"20002",
        "error_desc"=>"Upload img error",
    ),

    "30000" => array(
        "error_code"=>"30000",
        "error_desc"=>"发布商品失败",
    ),
    "30001" => array(
        "error_code"=>"30001",
        "error_desc"=>"编辑商品失败",
    ),
);



?>