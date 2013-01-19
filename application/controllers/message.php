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
        $this->load->model('Message_model');
    }


    /**
     * 发送私信
     */
    public function send(){

        $access_token = $this->input->get_post("access_token");
        $from_uid = $this->input->get_post("from_uid");
        $to_uid = $this->input->get_post("to_uid");
        $content = $this->input->get_post("content");


        $this->Message_model->insert($from_uid, $to_uid, $content);


    }

    /**
     * 获取私信列表
     */
    public function getList(){

        $access_token = $this->input->get_post("access_token");
        $uid = $this->input->get_post("uid");
        $count = $this->input->get_post("count");
        $page = $this->input->get_post("page");

        $list = $this->Message_model->getList($uid);

        echo json_encode($list);
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

        $detailList = $this->Message_model->show($uid, $other_uid);


        echo json_encode($detailList);

    }

    /**
     * 删除私信
     */
    public function del(){

        $access_token = $this->input->get_post("access_token");
        $uid = $this->input->get_post("uid");
        $other_uid = $this->input->get_post("other_uid");

        $this->Message_model->del($uid, $other_uid);

    }

    /**
     * 获取未读私信数量
     */
    public  function unread(){

    }
}
