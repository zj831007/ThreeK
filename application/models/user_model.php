<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:15
 * To change this template use File | Settings | File Templates.
 */
class User_model extends CI_Model{

    const USER_COLLECTTION = 'user';
    private $userCollection = null;

    function __construct(){
        parent::__construct();
        $this->userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);
    }
	/**
	 * @var unknown_type
	 */
    const userTableCnt = 10;
    /**
     * 
     * @var unknown_type
     */
    const USER_REG_OK = 0;
    const USER_EXISTS = 1;
    const USER_REG_FAIL = 2;
    
    /**
     * 注册新用户
     * @param $username
     * @param $password
     *
     */
    function insertNewUser($username, $password){
 		//默认注册OK
    	$ret = self::USER_REG_OK;
        //防止sql注入
    	$password = trim($password);
    	$password = md5($password);
        $uname = $this->db->escape($username);
        $upass = $this->db->escape($password);
        //判断username是否已注册
        $sql = "SELECT `id` FROM `uuid` where username = $uname limit 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0){
            //此用户已存在，错误处理 TODO
            $ret = self::USER_EXISTS;
        }else{
        	//开启
        	$this->db->trans_begin();
            $sql = "INSERT INTO `uuid`(`username`,`passwd`) VALUES($uname,$upass)";
            $this->db->query($sql);
            if( $this->db->affected_rows() ){
                $id  = $this->db->insert_id();
                $table = $this->_getTable($id);
                //向user表中插入数据：根据$id分表插入 TODO 
                $sql = "INSERT INTO `$table`(`userid`,`username`) VALUES($id,$uname)";
                $this->db->query($sql);
                if( $this->db->affected_rows() ){
                }else{
                	$ret = self::USER_REG_FAIL;
                }
            }else{
                //插入失败，错误处理 TODO
            	$ret = self::USER_REG_FAIL;
            }
            if ($this->db->trans_status() === FALSE){
            	$this->db->trans_rollback();
            	$ret = self::USER_REG_FAIL;
            }else{
            	$this->db->trans_commit();
            }
        }
        $r = array();
        $r['ret'] = $ret;
        if( self::USER_REG_OK == $ret){
        	$r['uid'] = $id;
        }
        return $r;
    }
	
    /**
     * 用户登录
     * @param $username
     * @param $password
     * 
     * @return 用户基本信息
     */
     function checkUserPasswd($username,$password){
     	
     	$password = trim($password);
     	$password = md5($password);
     	
     	$username = $this->db->escape($username);
     	$password = $this->db->escape($password);
     	
     	$sql = "SELECT * FROM `uuid` WHERE `username` = ".$username." AND `passwd` = $password";
     	$query = $this->db->query($sql);
     	if( 1 == $query->num_rows() ){
     		$r = current($query->result_array());
     		return $r;
     	}else{
     		return null;
     	}
     }
     /**
      * 根据用户名获取$uuid
      * @param $username
      * 
      * @return $uuid
      */
     private function _getUuid($username){
     	$sql = "SELECT `id` FROM `uuid` WHERE `username` = '".$username."'";
     	if( $this->db->query($sql) ){
     		$r = $this->db->row();
     		return $r->id;
     	}else{
     		return 0;
     	}
     }
     /**
      * 根据UID获取用户基本信息
      * @param $uuid
      * 
      * @return 用户基本信息
      */
     function getUserInfo($uuid){
     	$uuid = (int)$uuid;
     	$uuid = $this->db->escape($uuid);
     	$table = $this->_getTable($uuid);
     	$sql = "SELECT * FROM $table WHERE userid = $uuid";
     	$query = $this->db->query($sql);
     	if( $query->num_rows() > 0 ){
     		$r = current($query->result_array());
     		return $r;
     	}else{
     		return null;
     	}
     }
     
     /*
      * 
      */
     function updateUserInfo($uid,$icon ="",$avatar_orgin = "",$gender = "",$desc = "",$tel = "" ,$email = "",$nickname = ""){
     	$uid = (int)$uid;
     	$uid = $this->db->escape($uid);
     	$table = $this->_getTable($uid);
     	$sql = "UPDATE $table ";
     	$SET = "SET";
     	$COMMA = "";
     	if( false !== $gender){
     		$gender = (int)$gender;
     		$gender = $this->db->escape($gender);
     		$sql .= "$COMMA$SET `gender` = $gender ";
     		$SET = "";
     		$COMMA = ",";
     	}
     	if( false !== $desc){
     		$desc = $this->db->escape($desc);
     		$sql .= "$COMMA$SET `desc` = $desc ";
     		$SET = "";
     		$COMMA = ",";
     	}
     	if( false !== $icon){
     		$icon = $this->db->escape($icon);
     		$sql .= "$COMMA$SET `icon` = $icon ";
     		$SET = "";
     		$COMMA = ",";
     	}
         if( false !== $avatar_orgin){
             $avatar_orgin = $this->db->escape($avatar_orgin);
             $sql .= "$COMMA$SET `avatar_orgin` = $avatar_orgin ";
             $SET = "";
             $COMMA = ",";
         }
     	if( false !== $tel){
     		$tel = $this->db->escape($tel);
     		$sql .= "$COMMA$SET `tel` = $tel ";
     		$SET = "";
     		$COMMA = ",";
     	}
     	if( false !== $email){
     		$email = $this->db->escape($email);
     		$sql .= "$COMMA$SET `email` = $email ";
     		$SET = "";
     		$COMMA = ",";
     	}
     	if( false !== $nickname){
     		$nickname = $this->db->escape($nickname);
     		$sql .= "$COMMA$SET `nickname` = $nickname ";
     		$SET = "";
     		$COMMA = ",";
     	}
     	$sql .= " WHERE `userid` = $uid";
     	$query = $this->db->query($sql);
     	/*
     	if( $this->db->affected_rows() ){
     	}else{
     		return null;
     	}
     	*/
     	return $this->getUserInfo($uid);
     }
     
     /**
      * 根据UUID获取用户分表名
      * @param $uuid
      * 
      * @return 表明
      */
     private function _getTable($uuid){
     	$tableIndex = $uuid % self::userTableCnt;
     	return "user".sprintf("%02d",$tableIndex);
     }



    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //+++++++++++  Mongodb operation
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * 验证toekn与用户名是否有效
     * @param string $uid
     * @param string $token
     * @return boolean
     */
     function checkUidToken($uid,$token){
        $userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);
        $query = array('uid' => (int)$uid,'access_token' => $token);
        $tmp = $userCollection->findOne($query);
        if($tmp){
            //验证通过
            return true;
        }else{
            return false;
        }
    }


    /**
     * 生成Token 更改为登陆状态
     * @param unknown_type $uid
     * @return string
     */
     function genToken($uid){
        $ret = array();
        $userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);
        $ret['uid'] = $uid;
        $token = md5($uid.time().uniqid(mt_rand(10000,99999)));
        $ret['access_token'] = $token;
        $tmp = $userCollection->findOne(array("uid" => $ret['uid']));
        if($tmp){
            //登录过
            $userCollection->remove(array("uid" => $ret['uid']));
        }else{
            //第一次登录
        }
        $ret['status'] = 1;
        $userCollection->insert($ret);
        return $token;
    }
    /**
     * 判断用户登录状态
     * @param $uid 用户ID
     * @return 用户登录状态 1在线 0离线 
     */
    function getOnlineStatus($uid){
    	$ret = array();
    	$userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);
    	$query = array('uid' => (int)$uid);
    	$tmp = $userCollection->findOne($query);
    	if($tmp){
    		return (int)$tmp['status'];
    	}
    	return 0;
    }
    
    /**
     * @param $uid 用户id
     * @param $key key
     * @param $value value
     */
    function setUserMongoKV($uid,$key,$value){
    	$userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);
    	$query = array('uid' => (int)$uid);
    	$newdata = array('$set' => array("$key" => $value));
    	$tmp = $userCollection->update(array('uid'=>(int)$uid),$newdata);
    	if($tmp && 1 == $tmp['ok'] ){
    		return $tmp['ok'];
    	}else{
    		return 0;
    	}
    }
}
