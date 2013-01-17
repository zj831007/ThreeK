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

        $msgCollection = $this->mongodb->selectCollection(self::MSG_COLLECTTION);

        $msgobj = array(
            
        );




//        $doc = array(
//            "name" => "MongoDB",
//            "type" => "database",
//            "count" => 1,
//            "info" => (object)array( "x" => 203, "y" => 102),
//            "versions" => array("0.9.7", "0.9.8", "0.9.9")
//        );
//        $msgCollection->insert($doc);

        //$document = $msgCollection->findOne();
        //var_dump($document);

//        echo $msgCollection->count();

        //$a = array('a'=>'b');
        //$db->insert($a,array('safe'=>true));
        //echo $db;
        //$fuserInfo = $this->User_model->getUserInfo($fuid);
        //var_dump($fuserInfo);

    }


    /**
     * 返回单条私信内容
     * @param $msgid
     */
    function findone($msgid){


    }

    /**
     * 删除个人私信
     * @param $uid
     * @param $otherid
     */
    function del($uid, $otherid){


    }


    /**
     * 返回该用户的信息列表首页
     * @param $uid
     */
    function detailList($uid){


    }

    /**
     * 某一个用户和目标用户的聊天记录详细页面
     * @param $uid
     * @param $otherid
     */
    function show($uid, $otherid){


    }

    /**
     * 返回未读消息数
     * @param $uid
     * @param $st
     */
    function unreadCount($uid, $st){

    }
}
