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
    static $userTableCnt = 10;
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
 
    	$ret = USER_REG_OK;
        //防止sql注入
        $uname = $this->db->escape($username);
        $upass = $this->db->escape($password);

        //判断username是否已注册
        $sql = "SELECT `id` FROM `uuid` where username = '".$uname."' limit 1";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0){
            //此用户已存在，错误处理 TODO
            $ret = USER_EXISTS;
        }else{
        	//开启
        	$this->db->trans_start();
            $sql = "INSERT INTO `uuid`(`username`,`passwd`) VALUES('".$uname."',$upass)";
            $this->db->query($sql);
            if($this->db->affected_rows()){
                $id  = $this->db->insert_id();
                $table = $this->_getTable($id);
                //向user表中插入数据：根据$id分表插入 TODO 
                $sql = "INSERT INTO `$table`(`userid`,`username`) VALUES($id,'".$uname."')";
                if($this->db->query($sql)){
                	
                }else{
                	$ret = USER_REG_FAIL;
                }
            }else{
                //插入失败，错误处理 TODO
            	$ret = USER_REG_FAIL;
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE){
            	// 生成一条错误信息... 或者使用 log_message() 函数来记录你的错误信息
            	$ret = USER_REG_FAIL;
            }
        }
        $r = array();
        $r['ret'] = $ret;
        if( USER_REG_OK == $ret){
        	$r['uuid'] = $id;
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
     	$username = $this->db->escape($username);
     	$password = $this->db->escape($password);
     	
     	$sql = "SELECT * FROM `uuid` WHERE `username` = '".$username."' AND `passwd` = $password";
     	$query = $this->db->query($sql);
     	if( $query ){
     		$r = $query->result_array();
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
     	$uuid = $this->db->escape($uuid);
     	$tableIndex = $uuid % 10;
     	$sql = "SELECT * FROM user".sprintf("%02d",$tableIndex)." WHERE userid = $uuid";
     	if( $this->db->query($sql) ){
     		return $this->db->row();
     	}else{
     		return null;
     	}
     }
     
     /**
      * 根据UUID获取用户分表名
      * @param $uuid
      * 
      * @return 表明
      */
     private function _getTable($uuid){
     	$tableIndex = $uuid % User_model::userTableCnt;
     	return "user".sprintf("%02d",$tableIndex);
     }
}
