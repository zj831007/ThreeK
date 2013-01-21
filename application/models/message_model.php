<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:15
 * To change this template use File | Settings | File Templates.
 */
class Message_model extends CI_Model{

    const MSG_COLLECTION = 'message';
    const OP_MORE = -1;
    const OP_REFRESH = 1;

    function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->messageCol = $this->mongodb->selectCollection(self::MSG_COLLECTION);
    }

    /**
     * 发送私信
     * 将用户消息录入数据库,每次都会插入2条记录
     *   msgobj{
     *       from 发起用户id，当前用户的uid
     *       fromname 发起用户名称
     *       fromeface 发起用户头像
     *       to   目标用户id
     *       toname 发起用户名称
     *       toface 发起用户头像
     *       content 消息内容
     *       timestamp 当前时间戳
     *   }
     *
     * @param $fuid
     * @param $tuid
     * @param $content
     * @return 返回这条消息的内容
     */
    function insert($fuid, $tuid, $content){

        $msgobj = array(
            "from"     => $fuid,
            "fromname" => "",//TODO
            "fromface" => "",
            "to"       => $tuid,
            "toname"   => "",
            "toface"   => "",
            "content"  => $content,
            "timestamp"=> time()
        );




        //1,将发消息者作为belong存一条消息记录
        $msgobj1 = array_merge($msgobj,array());
        $msgobj1["belong"] = $fuid;

        //2,将获得消息者作为belong存一条消息记录
        $msgobj2 = array_merge($msgobj,array());
        $msgobj2['belong'] = $tuid;

        $this->messageCol->insert($msgobj1);
        $this->messageCol->insert($msgobj2);

        //更新或插入fuid, tuid的最新消息记录，供列表显示
        $msgobj['islist'] = 1;

        //1次save
        $msgobj['belong'] = $fuid;
        $msgobj['_id'] = $fuid.$tuid;
        $this->messageCol->save($msgobj);

        //2次save  取出消息接收者最新消息条数，加1 更新状态
        $lastStatus = $this->messageCol->findOne(array("_id" => $tuid.$fuid), array("news" => true));
        if(empty($lastStatus)){
            $news = 0;
        }else{
            $news = $lastStatus['news'];
        }
        $msgobj['belong'] = $tuid;
        $msgobj["_id"] = $tuid.$fuid;
        $msgobj["news"] = ++$news;
        $this->messageCol->save($msgobj);


    }


    /**
     * 返回单条私信内容
     * @param $msgid
     */
    function findone($msgid){
        $msgObj =  $this->messageCol->findOne(array("id" => $msgid));

        return $msgObj;
    }

    /**
     * 删除个人私信
     * @param $uid
     * @param $otherid
     */
    function del($uid, $otherid){

        $this->messageCol->remove(
            array("belong"=>$uid),
            array('$or' => array(array("from"=>$otherid), array("to"=>$otherid)))
        );
    }


    /**
     * 返回该用户的信息列表首页
     * @param $uid
     */
    function getList($uid,$op,$get_time,$count){

        $list = array();

        switch($op){
            case self::OP_MORE:
                $listCursor = $this->messageCol->find(
                    array("belong" => $uid, "islist" => 1,"timestamp" => array('$lt'=>$get_time))
                )->limit($count)->sort(
                    array("timestamp" => -1)
                );
                break;
            case self::OP_REFRESH:
                $listCursor = $this->messageCol->find(
                    array("belong" => $uid, "islist" => 1,"timestamp" => array('$gt'=>$get_time))
                )->limit($count)->sort(
                    array("timestamp" => -1)
                );
                break;
        }



        foreach ( $listCursor as $id => $value ){
            $list[] = $value;
        }


        return $list;
    }

    /**
     * 某一个用户和目标用户的聊天记录详细页面
     * @param $uid
     * @param $otherid
     */
    function show($uid, $otherid,$op,$get_time,$count){


        //返回聊天记录
        $detailList = array();

        switch($op){
            case self::OP_MORE:

                $detailListCursor = $this->messageCol->find(
                    array(
                        "belong" => $uid,
                        "islist" => array('$ne' => 1),
                        '$or'    => array(array("from" => $otherid), array("to" => $uid)),
                        "timestamp" => array('$lt'=>$get_time)
                    )
                )->limit($count)->sort(
                    array("timestamp" => -1)
                );
                break;
            case self::OP_REFRESH:
                $detailListCursor = $this->messageCol->find(
                    array(
                        "belong" => $uid,
                        "islist" => array('$ne' => 1),
                        '$or'    => array(array("from" => $otherid), array("to" => $uid)),
                        "timestamp" => array('$gt'=>$get_time)
                    )
                )->limit($count)->sort(
                    array("timestamp" => -1)
                );
                break;
        }


        foreach ( $detailListCursor as $id => $value ){
            $value["_id"] = $id;
            $detailList[] = $value;
        }

        //更新新消息计数
        $this->messageCol->update(
            array("_id" => $uid.$otherid),
            array('$inc' => array("news" => -count($detailList)))
        );

        return $detailList;
    }

    /**
     * 返回未读消息数
     * @param $uid
     * @param $st
     */
    function unreadCount($uid, $st){
        

    }
}
