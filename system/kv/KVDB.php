<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-16
 * Time: 上午10:32
 * To change this template use File | Settings | File Templates.
 */


function & MONGDB(){
    // Is the config file in the environment folder?
    if ( ! defined('ENVIRONMENT') OR ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php'))
    {
        if ( ! file_exists($file_path = APPPATH.'config/database.php'))
        {
            show_error('The configuration file database.php does not exist.');
        }
    }
    include($file_path);

    if ( ! isset($mongodb) OR count($mongodb) == 0)
    {
        show_error('No mongodb connection settings were found in the database config file.');
    }



        try{
            $mongdodbH = $mongodb['hostname'];
            $mongdodbP = $mongodb['port'];
            $mongodbObj = new Mongo("mongodb://$mongdodbH:$mongdodbP");

            $mongodbObj->selectDB("threek");

            return $mongodbObj;
        }catch (Exception $e){
            //TODO  mongodb链接出错 ，报警处理

        }



}

function &REDIS(){
    // Is the config file in the environment folder?
    if ( ! defined('ENVIRONMENT') OR ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php'))
    {
        if ( ! file_exists($file_path = APPPATH.'config/database.php'))
        {
            show_error('The configuration file database.php does not exist.');
        }
    }

    include($file_path);

    if ( ! isset($redis) OR count($redis) == 0)
    {
        show_error('No redus connection settings were found in the database config file.');
    }


        try{
            $redisH = $redis['hostname'];
            $redisP = $redis['port'];
            $redis = new Redis();
            $redis->connect("$redisH", $redisP);

            return $redis;
        }catch (Exception $e){
            //TODO  redis链接出错 ，报警处理

        }




}


