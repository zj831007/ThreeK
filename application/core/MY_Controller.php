<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//include_once LOGGER_PATH."/CLog.class.php";


abstract class MY_Controller extends CI_Controller {


    function __construct() {

        parent::__construct();
        $this->load->helper("commonfun");


        // CLog::debug($_SERVER['']);

    }


    public function index() {

        die('error');
    }

    protected  function  _validateToken($userid, $access_token){



    }
}

// END MY_Controller Class
 
/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */