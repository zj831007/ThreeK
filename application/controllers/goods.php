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

        parent::_validateToken();

        $uid = $this->input->get_post('uid');
        $goods_id = $this->input->get_post('goods_id');
        $title = $this->input->get_post('title');
        $desc  = $this->input->get_post('desc');
        $money = $this->input->get_post('money');
        $lon  = $this->input->get_post('lon');
        $lat = $this->input->get_post('lat');
        $status = $this->input->get_post('status');

        if(empty($money)) $money = 0; //免费商品

        if(empty($goods_id)){

            //判断用户在线商品数量，


            //发布新商品
            $goods_id = $this->Goods_model->insertNewGoods($uid, $title, $desc, $money, $lon, $lat);
            if($goods_id){

                //发布成功
                $res = array(
                    "uid" => $uid,
                    "goods_id" => $goods_id
                );
                echo json_encode($res);
            }else{
               //发布失败
               tkProcessError(30000);
            }

        }else{
            //编辑商品
            $rs = $this->Goods_model->updateGoods($uid, $goods_id, $title, $desc, $money, $lon, $lat, $status);
            if($rs){
                $res = array(
                    "uid" => $uid,
                    "goods_id" => $goods_id
                );
                echo json_encode($res);
            }else{
                //编辑失败
                tkProcessError(30001);
            }
        }

    }


    /**
     * 获取商品详细信息
     */
    function detail(){
        $goods_id = $this->input->get_post('goods_id');
        $goodsInfo = $this->Goods_model->getGoodDetail($goods_id);
        if($goodsInfo){

            $goodDetail = array(
                "goodsid" => $goodsInfo["goodsid"],
                "title" => $goodsInfo["title"],
                "desc" => $goodsInfo["desc"],
                "userid" => $goodsInfo["userid"],
                "price" => $goodsInfo["price"],
                "publishtime" => $goodsInfo["publishtime"],
                "status" => $goodsInfo["status"],
                "goodsid" => $goodsInfo["goodsid"],
                "goodsid" => $goodsInfo["goodsid"],
            );
            $gps = $goodsInfo["gps"];
            if(is_array($gps)){
                $goodDetail["lon"] = $gps["lon"];
                $goodDetail["lat"] = $gps["lat"];
            }

            echo json_encode($goodDetail);
        }else{
            tkProcessError(30002);
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