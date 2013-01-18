<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:15
 * To change this template use File | Settings | File Templates.
 */
class Message_model extends CI_Model{

    const MSG_COLLECTTION = 'message';

    function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->messageCol = $this->mongodb->selectCollection(self::MSG_COLLECTTION);
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
        $msgobj['belong'] = $fuid;
        $msgobj['_id'] = $fuid.$tuid;
        $msgobj['islist'] = 1;
        //1次save
        $this->messageCol->save($msgobj);

        //2次save
        $msgobj['belong'] = $tuid;
        $msgobj["_id"] = $tuid.$fuid;
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
            array('$or' => array("from"=>$otherid,"to"=>$otherid))
        );
    }


    /**
     * 返回该用户的信息列表首页
     * @param $uid
     */
    function detailList($uid){

        $list = $this->messageCol->find(
          array("belong" => $uid, "islist" => 1)
        ).sort(
            array("timestamp"=>-1)
        );

        return $list;
    }

    /**
     * 某一个用户和目标用户的聊天记录详细页面
     * @param $uid
     * @param $otherid
     */
    function show($uid, $otherid){

        $detailList = $this->messageCol->find(
            array(
                "belong" => $uid,
                "islist" => array('$ne' => 1),
                '$or'    => array("from" => $otherid, "to" => $otherid)
            )
        ).sort(
            array("_id" => -1)
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
