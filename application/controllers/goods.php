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

    /**
     * 发布商品
     */
    function publish(){
        $userId = $this->input->get_post('userId');
        $title = $this->input->get_post('title');
        $desc  = $this->input->get_post('desc');
        $price = $this->input->get_post('price');
        $lon  = $this->input->get_post('lon');
        $lat = $this->input->get_post('lat');

        // TODO 验证参数

        $goods = array(
            "userId" => $userId,
            "title" => $title,
            "desc" => $desc,
            "price"=> $price,
            "lon"=>$lon,
            "lat"=>$lat
        );
        $rt = $this->Goods_model->insertNewGoods($goods);
        if($rt == 1){
            echo "{'code':20000,'desc':'商品发布成功'}";
        }else{
            echo "{'code':10012,'desc':'商品发布错误'}";
        }

    }

    /**
     * 编辑商品
     */
    function edit(){

        $userId = $this->input->get_post('userId');

        $title = $this->input->get_post('title');

        $desc = $this->input->get_post('desc');

        $price = $this->input->get_post('price');

        $lon = $this->input->get_post('lon');

        $lat = $this->input->get_post('lat');

        $goods = array(

            "userId" => $userId,

            "title" => $title,

            "desc" => $desc,

            "price"=> $price,

            "lon"=>$lon,

            "lat"=>$lat

        );

        $this->Goods_model->updateGoods($goods);

    }



    /**
     * 获取商品详细信息
     */
    function detail(){
        $userId = $this->input->get_post('userId');
        $goodsId = $this->input->get_post('goodsId');
        $goodsInfo = $this->Goods_model->getGoods($userId,$goodsId);
        if($goodsId){
            echo json_encode($goodsInfo);
        }else{
            echo "";
        }
    }

    /**
     * 下架商品
     */
    function offline(){
        $userId = $this->input->get_post('userId');
        $goodsId = $this->input->get_post('goodsId');
        echo $this->Goods_model->offlineGoods($goodsId,$userId);
    }

    /**
     * 获取商品列表
     * 包括根据地理位置获取和获取某人的商品列表
     */
    function getList(){
        $access_token = $this->input->get_post('access_token');
        $count = $this->input->get_post('count');
        $page = $this->input->get_post('page');

        $filter = $this->input->get_post('filter');
        $keyword = $this->input->get_post('keyword');
        $status = $this->input->get_post('status');

        if($filter=="2"){
            $userId = $keyword;
            $goodList = $this->Goods_model->getAllGoodsByUser($userId,(int)$status,(int)$count,(int)$page);
            foreach($goodList as $row){
                echo json_encode($row);
                echo "<br/>";
            }
        }elseif($filter=="1"){
            $lon = $this->input->get_post('lon');
            $lat = $this->input->get_post('lat');
            $near = $this->Goods_model->getNearGoodsByLocal((int)$lon,(int)$lat,(int)$count,(int)$page,(int)$status,$keyword);
            while($near->hasNext()){
                echo json_encode($near->getNext());
                echo "<br/>";
            }


        }
    }
}