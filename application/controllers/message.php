<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/* Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:18
 * To change this template use File | Settings | File Templates.
 */
class Message extends MY_Controller{


    function __construct(){

        parent::__construct();
        $this->load->model('User_model');
    }


    /**
     * 发送私信
     */
    public function send(){

        $access_token = $this->input->get_post("access_token");
        $from_uid = $this->input->get_post("from_uid");
        $to_uid = $this->input->get_post("to_uid");
        $content = $this->input->get_post("content");
        $time=date('Y-m-d H:i:s',time());





    }

    /**
     * 获取私信列表
     */
    public function getList(){

        $access_token = $this->input->get_post("access_token");
        $uid = $this->input->get_post("uid");
        $count = $this->input->get_post("count");
        $page = $this->input->get_post("page");



    }

    /**
     * 获取与某人的私信列表
     */
    public function getDetailList(){

        $access_token = $this->input->get_post("access_token");
        $uid = $this->input->get_post("uid");
        $before_time = $this->input->get_post("before_time");
        $other_uid = $this->input->get_post("other_uid");
        $count = $this->input->get_post("count");


    }

    /**
     * 删除私信
     */
    public function del(){

        $access_token = $this->input->get_post("access_token");
        $uid = $this->input->get_post("uid");
        $other_uid = $this->input->get_post("other_uid");



    }


}
