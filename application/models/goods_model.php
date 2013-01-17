<?php
/**
 * Created by JetBrains PhpStorm.
 * User: linsong
 * Date: 13-1-16
 * Time: 下午8:10
 * To change this template use File | Settings | File Templates.
 */
class Goods_model extends CI_Model{

    private $table_name_prefix = "goods0";

    function __construct(){
        parent::__construct();
        //$this->load->database();
    }

    /**
     * 发布商品
     * @return mixed
     */
    function insertNewGoods(){
        $userId = $this->input->get_post('userId');
        $goodsId = "".$userId+time()+random(5);
        $status = 0;
        $title = $this->input->get_post('title');
        $desc  = $this->input->get_post('desc');
        $price = $this->input->get_post('price');
        $lon  = $this->input->get_post('lon');
        $lat = $this->input->get_post('lat');
        $publish_time = date("Y/m/d H:i:s");
        $modify_time = $publish_time;
        $data = array(
            'goodsid'=>$goodsId,
            'title'=>$title,
            'desc' => $desc,
            'userid' => $userId,
            'price' => $price,
            'publishtime'=>$publish_time,
            'modifytime'=>$modify_time,
            'lon'=>$lon,
            'lat'=>$lat,
            'status'=>$status
        );
        $table_name = $this->getTableName($userId);

        return $this->db->insert($table_name,$data);
    }

    /**
     *编辑商品
      */
    function updateGoods(){
        $userId = $this->input->get_post('userId');
        $goodsId = $this->input->get_post('goodsId');
        $title = $this->input->get_post('title');
        $desc  = $this->input->get_post('desc');
        $price = $this->input->get_post('price');
        $lon  = $this->input->get_post('lon');
        $lat = $this->input->get_post('lat');
        $modify_time = date("Y/m/d H:i:s");
        $table_name = $this->getTableName($userId);

        $sql = "UPDATE ".$table_name." set title ='".$title."',desc='".$desc."',price='".$price."',lon='".$lon."',lat='".$lat."',modifytime='".$modify_time."' where goodsid='".$goodsId."'";

        $this->db->query($sql);
    }

    /**
     * 获取商品详情
     * @param $goodsId
     * @param $userId
     * @return mixed
     */
    function getGoods($goodsId,$userId){

        $rs = $this->db->get_where($this->getTableName($userId),array("goodsid" => $goodsId));
        return $rs->row_array();
    }

    /**
     * 下架商品
     * @param $goodsId
     * @param $userId
     */
    function offlineGoods($goodsId,$userId){
        $sql = "UPDATE ".$this->getTableName($userId)." set status=1 where goodsid='".$goodsId."'";
        $this->db->query($sql);
    }

    private function getTableName($userId){
        return $this->table_name_prefix.($userId % 10);
    }
}