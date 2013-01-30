<?php
/**
 * Created by JetBrains PhpStorm.
 * User: linsong
 * Date: 13-1-16
 * Time: 下午8:09
 * To change this template use File | Settings | File Templates.
 */
class Goods extends MY_Controller{

    const MAX_PUBLISH_GOODS_NUM = 5;  //能免费发布最在商品数量
    const DEFAULT_LIST_COUNT = 10;  //默认列表条数

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
            $currCount = $this->Goods_model->getAllGoodsCntByUser($uid);
            if($currCount >= self::MAX_PUBLISH_GOODS_NUM){
                tkProcessError(30004);
            }

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
                "bpic" => $goodsInfo["bpic"],
                "spic" => $goodsInfo["spic"],
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
        parent::_validateToken();

        $uid = $this->input->get_post('uid');
        $goodsId = $this->input->get_post('goods_id');
        $rs =  $this->Goods_model->offlineGoods($goodsId,$uid);

        if($rs){
            //操作成功
            tkProcessError(88888);
        }else{
            //下架失败
            tkProcessError(30003);
        }
    }

    /**
     * 获取商品列表
     * 包括根据地理位置获取和获取某人的商品列表
     */
    function getList(){

        $count = $this->input->get_post('count');
        if(empty($count))
            $count = self::DEFAULT_LIST_COUNT;

        $get_time = $this->input->get_post('get_time');
        if(empty($get_time))
            $get_time = time();

        $op = $this->input->get_post('op');
        if(empty($op))
            $op = -1;  //默认显示更多


        $filter = $this->input->get_post('filter');
        $keyword = $this->input->get_post('keyword');
        $status = $this->input->get_post('status');
        $lon = $this->input->get_post('lon');
        $lat = $this->input->get_post('lat');


        if($filter=="2"){
            //用户发布的商品

            $userId = $keyword;
            $goodList = $this->Goods_model->getAllGoodsByUser($userId, $status, $get_time, $count, $op);

            if(is_array($goodList)){
                echo json_encode($goodList);
            }else{
                tkProcessError(30005);
            }
        }elseif($filter=="1"){
            //周边商品

            $nearList = $this->Goods_model->getNearGoodsByLocal($lon, $lat, $keyword, $get_time, $count, $op);

            if(is_array($nearList)){
                echo json_encode($nearList);
            }else{
                tkProcessError(30005);
            }

        }
    }
}