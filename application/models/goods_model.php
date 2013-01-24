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
    const GOODS_STATUS_ONLINE = 1;
    const GOODS_STATUS_OFFLINE = 0;

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
                'lon'=> $lon,
                'lat'=> $lat
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
            $title = $this->db->escape($title);
            $sql .= "$COMMA$SET `title` = $title ";
            $SET = "";
            $COMMA = ",";
        }

        if( false !== $desc){
            $desc = $this->db->escape($desc);
            $sql .= "$COMMA$SET `desc` = $desc ";
            $SET = "";
            $COMMA = ",";
        }

        if( false !== $money){
            $money = $this->db->escape($money);
            $sql .= "$COMMA$SET `price` = $money ";
            $SET = "";
            $COMMA = ",";
        }

        if( false !== $lon){
            $lon = $this->db->escape($lon);
            $sql .= "$COMMA$SET `lon` = $lon ";
            $SET = "";
            $COMMA = ",";
        }

        if( false !== $lat){
            $lat = $this->db->escape($lat);
            $sql .= "$COMMA$SET `lat` = $lat ";
            $SET = "";
            $COMMA = ",";
        }

        if( false !== $status){
            $status = $this->db->escape($status);
            $sql .= "$COMMA$SET `status` = $status ";
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
            }
            if($lon){
                $mongoData['gps'] = array(
                    'lon'=> $lon,
                    'lat'=> $lat
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
            var_dump($r);
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
        $goods_id = $this->db->escape($goods_id);
        $sql = "replace into goods_pic set bigpic='".$pic."', smallpic='".$small_pic."', goodsid=".$goods_id;
        $this->db->query($sql);
    }
    
    /**
     * 获取用户所有在架商品
     * @param $userId
     * @return $total 商品数
     */
    function getAllGoodsCntByUser( $userId ){
    	$sql = "SELECT count(*) as `total` from ".$this->getTableName($userId)." where userid=".$userId." and status= 1";
    	$rs =  $this->db->query($sql)->result();
    	if($rs){
    		return (int)$rs[0]->total;
    	}
    	return 0;
    }
    
}