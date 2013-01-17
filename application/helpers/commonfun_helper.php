<?php
/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-15
 * Time: 上午10:48
 * To change this template use File | Settings | File Templates.
 */




if(!function_exists('tkProcessError')){

    function tkProcessError($code){
        $CI = &get_instance();
        $errDef = $CI->config->item('error_def');

        $err = $errDef[$code];
        echo json_encode($err);

        exit();
    }

}