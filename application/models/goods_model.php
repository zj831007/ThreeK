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

    const GOOD_COLLECTION = "goods";
    function __construct(){
        parent::__construct();
        $this->goodsCol = $this->mongodb->selectCollection(self::GOOD_COLLECTION);
    }

    /**
     * 发布商品
     * @return mixed
     */
    function insertNewGoods($goods){
        $userId = $goods['userId'];

        $goodsId = "".$userId.time().rand(10000,99999);

        $status = 0;

        $publish_time = date("Y/m/d H:i:s");
        $modify_time = $publish_time;

        $goods['goodsid'] = $goodsId;
        $goods['status'] = 0;
        $goods['publishtime'] = $publish_time;
        $goods['modifytime'] = $modify_time;

        $mongoData = array(
            'goodsid' => $goodsId,
            'title' => $goods['title'],
            'desc' => $goods['desc'],
            'userid' => $userId,
            'price' => $goods['price'],
            'publishtime'=>$publish_time,
            'modifytime'=>$modify_time,
            'gps' => array(
                'lon'=> (double)$goods['lon'],
                'lat'=> (double)$goods['lat']
            ),
            'status'=>$status
        );
        $table_name = $this->getTableName($userId);
        $insertRs = $this->db->insert($table_name,$goods);
        $this->goodsCol->save($mongoData);
        return $insertRs;
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
    function getGoods($userId,$goodsId){
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
        $mongoQuery = array(
          "goodsid"=>$goodsId
        );
        $targetGoods = $this->goodsCol->findOne($mongoQuery)->getNext();
        $targetGoods["status"] = 1;
        $this->goodsCol->save($targetGoods);
        return $this->db->query($sql);
    }

    /**
     * 获取某人的全部商品列表
     * @param $userId
     * @return mixed
     */
    function getAllGoodsByUser($userId,$status,$pageSize,$pageNo){

        $sql = "SELECT * from ".$this->getTableName($userId)." where userid=".$userId;
        if($status != 2 ){
            $sql = $sql." and status=".$status;
        }
        $sql = $sql." order by publishtime desc limit ".(($pageNo-1)*$pageSize).",". $pageSize;
        $rs =  $this->db->query($sql)->result();
        return $rs;
    }

    /**
     * 获取附近商品信息
     * @param $lon
     * @param $lat
     */
    function getNearGoodsByLocal($lon,$lat,$pageSize,$pageNo,$status,$keyword){
        $query =  array(
            'gps'=>array("\$near"=> array('lon'=>$lon,'lat'=>$lat))
        );
        if($status != 2){
            $query['status'] = $status;
        }
        if($keyword != ""){
            $query["desc"] = $keyword;
        }
       return  $this->goodsCol->find($query)->skip(($pageNo-1)*$pageSize)->limit($pageSize);
    }
    /**
     * 根据用户id获取表名
     * @param $userId
     * @return string
     */
    private function getTableName($userId){
        return $this->table_name_prefix.($userId % 10);
    }
}