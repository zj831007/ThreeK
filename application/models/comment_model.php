<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:15
 * To change this template use File | Settings | File Templates.
 */
class Comment_model extends CI_Model{

    const COMMENT_COLLECTION = "comment";
    const MORE_COMMENT = -1;
    const REFRESH_COMMENT = 1;

    function __construct(){
        parent::__construct();
        $this->commentCol = $this->mongodb->selectCollection(self::COMMENT_COLLECTION);
        $this->load->model('User_model');
    }


    /**
     * 插入咨询
     *   commentobj{
     *        c_ID      咨询ID（集合对象ID,mongodb自动生成）
     *        goodsid  咨询商品ID
     *        uid       咨询人ID
     *        avatar     咨询人头像
     *        nickname  咨询人昵称
     *        ask       咨询问题
     *        asktime  咨询时间
     *        reply{
     *          answer    回复
     *          answertime    回复时间(int)
     *        }
     *   }
     *
     * @param $fuid
     * @param $tuid
     * @param $content
     * @return 返回这条消息的内容
     */
    function insert($uid, $goods_id, $ask){
        $userInfo = $this->User_model->getUserInfo($uid);

        $commentobj = array(
            "goods_id" => $goods_id,
            "uid"      => $uid,
            "avatar"    => $userInfo["icon"],
            "nickname" => $userInfo["nickname"],
            "ask"      => $ask,
            "asktime" => time(),
        );

        $this->commentCol->insert($commentobj);

    }

    /**
     * 回复咨询
     * @param $comment_id
     * @param $answer
     */
    function insertReply($comment_id, $answer){

        $this->commentCol->update(
            array("_id" => new MongoId($comment_id)),
            array('$set'=> array("reply" =>array(
                "answer"     => $answer,
                "answertime" =>time()
            )))
        );
    }

    /**
     * 获取咨询列表
     * @param $goods_id
     * @param $op
     * @param $get_time
     * @param $count
     * @return array
     */
    function getList($goods_id,$op,$get_time,$count){

        $list = array();

        switch($op){
            case self::MORE_COMMENT:
                $listCursor = $this->commentCol->find(
                    array("goods_id" => $goods_id,"asktime" => array('$lt'=>$get_time))
                )->limit($count)->sort(
                    array("asktime" => -1)
                );
                break;
            case self::REFRESH_COMMENT:
                $listCursor = $this->commentCol->find(
                    array("goods_id" => $goods_id,"asktime" => array('$gt'=>$get_time))
                )->limit($count)->sort(
                    array("asktime" => -1)
                );
                break;
                break;

        }

        foreach ( $listCursor as $id => $value ){
            $comment = array(
                "c_id" => $id,
                "uid"  => $value["uid"],
                "avatar"=> $value["avatar"],
                "nickname"=>$value["nickname"],
                "ask"     =>$value["ask"],
                "ask_time"=>$value["asktime"]
            );

            if(!empty($value["reply"])){
                $comment["reply"] = $value["reply"];
            }
            $list[] = $comment;
        }

        return $list;
    }

}
