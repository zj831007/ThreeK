<?php
/**
 * Created by JetBrains PhpStorm.
 * User: linsong
 * Date: 13-1-16
 * Time: 下午8:09
 * To change this template use File | Settings | File Templates.
 */
class Goods extends MY_Controller{

     function __construct(){
        parent::__construct();
        $this->load->model("Goods_model");
    }
    function publish(){
        $userId = $this->input->get_post('userId');
        $title = $this->input->get_post('title');
        $desc  = $this->input->get_post('desc');
        $price = $this->input->get_post('price');
        $lon  = $this->input->get_post('lon');
        $lat = $this->input->get_post('lat');
        // TODO 验证参数
         $this->Goods_model->insertNewGoods();
    }

    function edit(){
        $this->Goods_model->updateGoods();
    }

    function detail(){
        $userId = $this->input->get_post('userId');
        $goodsId = $this->input->get_post('goodsId');
        $goodsInfo = $this->Goods_model->getGoods($userId,$goodsId);
        if($goodsId){
            echo json_encode($goodsId);
        }else{
            echo "";
        }
    }
}