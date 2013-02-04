<?php
/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-20
 * Time: 上午11:14
 * To change this template use File | Settings | File Templates.
 */
class Comment extends MY_Controller{

    const DEFAULT_LIST_COUNT = 10;

    function __construct(){
        parent::__construct();
        $this->load->model("Comment_model");
    }


    /**
     * 商品咨询信息列表
     */
    function getList(){
        $goods_id = $this->input->get_post('goods_id');

        parent::_validateGoodsID($goods_id);

        $count = $this->input->get_post('count');
        if(empty($count))
            $count = self::DEFAULT_LIST_COUNT; //默认10条

        $get_time = $this->input->get_post('get_time');
        if(empty($get_time))
            $get_time = time();  //默认当前时间

        $op = $this->input->get_post('op');
        if(empty($op))
            $op = -1;  //默认显示更多

        $list = $this->Comment_model->getList($goods_id,$op,$get_time,$count);

        echo json_encode($list);
    }


    /**
     * 回复咨询
     */
    function reply(){

        $c_id = $this->input->get_post('c_id');
        $answer = $this->input->get_post('answer');

        parent::_validateToken();


        $this->Comment_model->insertReply($c_id, $answer);

        //操作成功：
        tkProcessError("88888");
    }

    /**
     * 咨询
     */
    function post(){
        $goods_id = $this->input->get_post("goods_id");
        $uid = $this->input->get_post("uid");
        $question = $this->input->get_post("question");

        parent::_validateToken();
        parent::_validateGoodsID($goods_id);

        $this->Comment_model->insert($uid, $goods_id, $question);
        //操作成功：
        tkProcessError("88888");

    }



}
