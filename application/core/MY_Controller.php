<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//include_once LOGGER_PATH."/CLog.class.php";


abstract class MY_Controller extends CI_Controller {


    function __construct() {

        parent::__construct();
        $this->load->helper("commonfun");
        $this->load->model('User_model');
        $this->load->model('Goods_model');
        // CLog::debug($_SERVER['']);

    }


    public function index() {

        die('error');
    }

    /**
     * token校验
     */
    protected  function  _validateToken(){

        $uid = $this->input->get_post('uid');
        $token = $this->input->get_post('access_token');

        if(! $this->User_model->checkUidToken($uid, $token)){
            //操作失败,登录过期
            tkProcessError("10005");
        }

    }

    /**
     * UID校验
     */
    protected  function _validateUID($uid){
        if(! $this->User_model->getUserInfo($uid)){
            //uid不存在
            tkProcessError("10012");
        }
    }

    /**
     * Goods ID校验
     */
    protected function  _validateGoodsID($goods_id){
        if(! $this->Goods_model->getGoodDetail($goods_id)){
            //goodsid不存在
            tkProcessError("30006");
        }
    }

}

// END MY_Controller Class
 
/* End of file Controller.php */
/* Location: ./application/core/MY_Controller.php */