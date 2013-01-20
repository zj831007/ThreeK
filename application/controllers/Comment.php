<?php
/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-20
 * Time: 上午11:14
 * To change this template use File | Settings | File Templates.
 */
class Comment extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model("Comment_model");
    }


    /**
     * 商品咨询信息列表
     */
    function getList(){
        $goods_id = $this->input->get_post('goods_id');
        $count = $this->input->get_post('count');
        $page = $this->input->get_post('page');

        $list = $this->Comment_model->getList($goods_id);

        echo json_encode($list);
    }


    /**
     * 回复咨询
     */
    function reply(){
        $c_id = $this->input->get_post('c_id');
        $answer = $this->input->get_post('answer');

        $this->Comment_model->insertReply($c_id, $answer);

    }

    /**
     * 咨询
     */
    function post(){
        $goods_id = $this->input->get_post("goods_id");
        $uid = $this->input->get_post("uid");
        $question = $this->input->get_post("question");


        $this->Comment_model->insert($uid, $goods_id, $question);

    }



}
