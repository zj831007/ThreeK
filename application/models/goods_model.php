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
    const GOODS_STATUS_ONLINE = 1;   //商品发布中
    const GOODS_STATUS_OFFLINE = 0;  //商品下架

    const OP_MORE = -1;
    const OP_REFRESH = 1;


    /**
     *
     * db.goods.ensureIndex({"gps":"2d","publishtime":-1,"title":1})
     */
    function __construct(){
        parent::__construct();
        $this->goodsCol = $this->mongodb->selectCollection(self::GOOD_COLLECTION);
    }

    /**
     * 发布商品
     * @return mixed
     */
    function insertNewGoods($uid, $title, $desc, $money, $lon, $lat){

        if(empty($desc)) $desc = "";
        if(empty($title)) $title = "";

        $goodsId = "".$uid.time().rand(10000,99999);

        $status = self::GOODS_STATUS_ONLINE;
        $publishtime = time();

        $mongoData = array(
            'goodsid' => $goodsId,
            'title' => $title,
            'desc' => $desc,
            'userid' => $uid,
            'price' => $money,
            'publishtime'=> $publishtime,
            'gps' => array(
                'lon'=> doubleval($lon),
                'lat'=> doubleval($lat)
            ),
            'status'=> $status
        );
        $table_name = $this->getTableName($uid);

        $sql = "insert into ".$table_name.
               "   set `title` =".$this->db->escape($title).
               ", `desc`=".$this->db->escape($desc).
               ", `price`=".$this->db->escape($money).
               ", `lon`=".$this->db->escape($lon).
               ", `lat`=".$this->db->escape($lat).
               ", `goodsid`=".$this->db->escape($goodsId).
               ", `userid`=".$this->db->escape($uid).
               ", `publishtime`=".$this->db->escape($publishtime).
               ", `status`=".$this->db->escape($status)."";
        $this->db->query($sql);
        if($this->db->affected_rows()){

            //插入成功，mongodb操作
            $this->goodsCol->save($mongoData);

            return $goodsId;
        }else{
            //插入失败
            return null;
        }
    }

    /**
     *编辑商品
      */
    function updateGoods($uid, $goods_id, $title, $desc, $money, $lon, $lat, $status){


        $table_name = $this->getTableName($uid);

        $sql = "UPDATE $table_name ";
        $SET = "SET";
        $COMMA = "";
        if( false !== $title){
            $title1 = $this->db->escape($title);
            $sql .= "$COMMA$SET `title` = $title1 ";
            $SET = "";
            $COMMA = ",";
        }

        if( false !== $desc){
            $desc1 = $this->db->escape($desc);
            $sql .= "$COMMA$SET `desc` = $desc1 ";
            $SET = "";
            $COMMA = ",";
        }

        if( false !== $money){
            $money1 = $this->db->escape($money);
            $sql .= "$COMMA$SET `price` = $money1 ";
            $SET = "";
            $COMMA = ",";
        }

        if( false !== $lon){
            $lon1 = $this->db->escape($lon);
            $sql .= "$COMMA$SET `lon` = $lon1 ";
            $SET = "";
            $COMMA = ",";
        }

        if( false !== $lat){
            $lat1 = $this->db->escape($lat);
            $sql .= "$COMMA$SET `lat` = $lat1 ";
            $SET = "";
            $COMMA = ",";
        }

        if( false !== $status){
            $status1 = $this->db->escape($status);
            $sql .= "$COMMA$SET `status` = $status1 ";
            $SET = "";
            $COMMA = ",";
        }

        $sql .= " WHERE `goodsid` = $goods_id";
        $this->db->query($sql);
        if($this->db->affected_rows()){

            //更新成功，更新mongodb
            $mongoData = $this->goodsCol->findOne(array(
                'goodsid'=>$goods_id
            ));
            if($title){
                $mongoData['title'] = $title;
            }
            if($desc){
                $mongoData['desc'] = $desc;
            }
            if($uid){
                $mongoData['userid'] = $uid;
            }
            if($money){
                $mongoData['price'] = $money;
            }
            if($status){
                $mongoData['status'] = $status;
                if($status == self::GOODS_STATUS_ONLINE){
                    $mongoData['publishtime'] = time();
                }
            }

            if($lon){
                $mongoData['gps'] = array(
                    'lon'=> doubleval($lon),
                    'lat'=> doubleval($lat)
                );
            }

            $mongoData = $this->goodsCol->save($mongoData);

            return true;
        }else{
            //插入失败
            return null;
        }
    }



    /**
     * 获取商品详情
     * @param $goodsId
     * @param $userId
     * @return mixed
     */
    function getGoodDetail($goodsId){
        $detail = $this->goodsCol->findOne(array(
            "goodsid" => $goodsId
        ));
        return $detail;
    }

    /**
     * 下架商品
     * @param $goodsId
     * @param $userId
     */
    function offlineGoods($goodsId,$userId){
        $sql = "UPDATE ".$this->getTableName($userId)." set status=".self::GOODS_STATUS_OFFLINE." where goodsid='".$goodsId."'";
        $this->db->query($sql);
        if($this->db->affected_rows()){
           return  $this->goodsCol->update(
                array(
                "goodsid"=>$goodsId
            ), array(
                '$set' => array("status" => self::GOODS_STATUS_OFFLINE)
            ));
        }else{
            return null;
        }

    }

    /**
     * 获取某人的全部商品列表
     * @param $userId
     * @return mixed
     */
    function getAllGoodsByUser($userId, $status, $get_time, $count, $op){

        $list = array();

        switch($op){
            case self::OP_MORE:

                $listCursor = $this->goodsCol->find(
                    array(
                        "userid"=>"$userId",
                        "status"=>intval($status),
                        "publishtime" => array('$lt'=>$get_time)
                    )
                )->limit($count)->sort(
                    array("publishtime" => -1)
                );
                break;
            case self::OP_REFRESH:
                $listCursor = $this->goodsCol->find(
                    array(
                        "userid"=>"$userId",
                        "status"=>intval($status),
                        "publishtime" => array('$gt'=>$get_time)
                    )
                )->limit($count)->sort(
                    array("publishtime" => -1)
                );
                break;
        }

        foreach ( $listCursor as $id => $value ){
            $value["_id"] = $id;
            $list[] = $value;
        }
        return $list;
    }

    /**
     * 获取附近商品信息
     * @param $lon
     * @param $lat
     */
    function getNearGoodsByLocal($lon, $lat, $keyword, $get_time, $count, $op){

        $list = array();

        $query =  array(
            'gps'=>array("\$near"=> array('lon'=>$lon,'lat'=>$lat))
        );

        $query['status'] = self::GOODS_STATUS_ONLINE;
        if($keyword != ""){
            $query["title"] = new MongoRegex("/".$keyword."/i");
        }

        switch($op){
            case self::OP_MORE:
                $query["publishtime"] = array('$lt'=>$get_time);

                $listCursor = $this->goodsCol->find($query)->limit($count)->sort(
                    array("publishtime" => -1)
                );
                break;
            case self::OP_REFRESH:
                $query["publishtime"] = array('$gt'=>$get_time);

                $listCursor = $this->goodsCol->find($query)->limit($count)->sort(
                    array("publishtime" => -1)
                );
                break;
        }

        foreach ( $listCursor as $id => $value ){
            $value["_id"] = $id;
            $list[] = $value;
        }
        return $list;
    }
    /**
     * 根据用户id获取表名
     * @param $userId
     * @return string
     */
    private function getTableName($userId){
        return $this->table_name_prefix.($userId % 10);
    }



    /**
     * 获取商品图片
     * @param $goods_id
     */
    function getGoodsPics($goods_id){
        $goods_id = $this->db->escape($goods_id);

        $sql = "select * from goods_pic where goodsid=$goods_id limit 1";
        $query = $this->db->query($sql);
        if( $query->num_rows() > 0 ){
            $r = current($query->result_array());
            return $r;
        }else{
            return null;
        }
    }

    /**
     * 替换商品图片
     * @param $goods_id
     * @param $pic
     * @param $small_pic
     */
    function replaceGoodsPic($goods_id, $pic, $small_pic){
        $old_goods_id = $goods_id;
        $goods_id = $this->db->escape($goods_id);
        $sql = "replace into goods_pic set bigpic='".$pic."', smallpic='".$small_pic."', goodsid=".$goods_id;
        $this->db->query($sql);

        $affectCount = $this->db->affected_rows();
        if($affectCount){
            //更新成功，更新mongodb

            $img_upload_base_path = $this->config->item("img_upload_base_path");
            $this->goodsCol->update(
                array("goodsid" => $old_goods_id),
                array('$set' => array("bpic" => $img_upload_base_path.$pic, "spic" => $img_upload_base_path.$small_pic))
            );

        }
    }
    
    /**
     * 获取用户所有在架商品
     * @param $userId
     * @return $total 商品数
     */
    function getAllGoodsCntByUser( $userId ){

        $count = $this->goodsCol->find(array("userid"=>$userId, "status" => self::GOODS_STATUS_ONLINE))->count();

    	return $count;
    }
    
}