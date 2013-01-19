<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:15
 * To change this template use File | Settings | File Templates.
 */
class User_model extends CI_Model{


    function __construct(){
        parent::__construct();
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
     function updateUserInfo($uid,$gender,$desc,$icon,$tel,$email,$nickname){
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
}
