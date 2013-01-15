<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//include_once LOGGER_PATH."/CLog.class.php";


abstract class MY_Controller extends CI_Controller {

    protected  $mongodb;
    protected  $redis;


    function __construct() {

        parent::__construct();
        $this->load->helper("commonfun");


        //mongodb
        $mongdodbConf = $this->config->item('mongodb');
        $mongdodbH = $mongdodbConf['hostname'];
        $mongdodbP = $mongdodbConf['port'];


        $this->mongodb = new Mongo("mongodb://$mongdodbH:$mongdodbP");
        $this->mongodb->selectDB("threek");


        $redisConf = $this->config->item('redis');
        $redisH = $redisConf['hostname'];
        $redisP = $redisConf['port'];

        //redis
//        $this->redis = new Redis();
//        $this->redis->connect("$redisH", $redisP);

        // CLog::debug($_SERVER['']);

        //$this->load->helper(array('form', 'url', 'date', 'string', 'file', 'directory', 'language'));
        //$this->load->library('upload');


    }


    public function index() {

        die('error');
    }

}

// END MY_Controller Class
 
/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */