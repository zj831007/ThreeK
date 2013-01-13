<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include_once LOGGER_PATH."/CLog.class.php";


abstract class MY_Controller extends CI_Controller {


    function __construct() {

        parent::__construct();

        $this->load->helper(array('form', 'url', 'date', 'string', 'file', 'directory', 'language'));
        $this->load->library('upload');


    }


    public function index() {

        die('error');
    }

}

// END MY_Controller Class
 
/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */