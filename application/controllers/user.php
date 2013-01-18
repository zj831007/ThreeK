<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/* Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:18
 * To change this template use File | Settings | File Templates.
 */
class User extends MY_Controller{

	
	const USER_COLLECTTION = 'user';
	
    function __construct(){

        parent::__construct();
        $this->load->model('User_model');
    }

    private function _genToken($uid){
    	$ret = array();
    	$userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);
    	$ret['uid'] = $uid;
    	$token = uniqid(mt_rand(10000,99999));
    	$ret['access_token'] = $token;
    	$tmp = $userCollection->findOne(array("uid" => $ret['uid']));
    	if($tmp){
    		//登录过
    		$userCollection->remove(array("uid" => $ret['uid']));
    	}else{
    		//第一次登录
    	}
    	$userCollection->insert($ret);
    	return $token;
    }

    /**
     * 用户注册
     * 访问方法：http://localhost/threek/index.php/user/reg?account=3&password=eeeqr
     */
    public function reg(){
        $username = $this->input->get_post("account");
        $password = $this->input->get_post("password");
        //效验参数：TODO
        $ret = $this->User_model->insertNewUser($username, $password);
        if(isset($ret['uid'])){
        	$token = $this->_genToken($ret['uid']);
        	$ret['access_token'] = $token;
        }
        echo json_encode($ret);
    }
    
    /** 
     * 用户登录
     * 访问方法：http://localhost/threek/index.php/user/login?account=3&password=eeeqr
     */
	public function login(){
		$ret = array();
		$username = $this->input->get_post("account");
		$password = $this->input->get_post("password");
		$userInfo = $this->User_model->checkUserPasswd($username,$password);
		if($userInfo){
			//login_success
			$ret['uid'] = $userInfo['id'];
			$token = $this->_genToken($ret['uid']);
			$ret['access_token'] = $token;
		}else{
			//login failed
			$ret['uid'] = "";
			$ret['access_token'] = "";
		}
		echo json_encode($ret);
	}

	/**
	 * 用户注销
	 * 访问方法：http://localhost/threek/index.php/user/logout?uid=3&access_token=123
	 */
	public function logout(){
		$uid = $this->input->get_post("uid");
		$token = $this->input->get_post("access_token");
		$userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);
		$userCollection->remove( array("uid" => $uid, "access_token"=> $token));
		echo json_encode(array());
	}
	/**
	 * 用户注销
	 * 访问方法：http://localhost/threek/index.php/user/status?uid=3&access_token=123&op=1
	 */
	public function status(){
		
	}
	/**
	 * 用户注销
	 * 访问方法：http://localhost/threek/index.php/user/status?uid=3&access_token=123&op=1
	 */
	public function profile(){
		
	}
	/**
	 * 用户注销
	 * 访问方法：http://localhost/threek/index.php/user/status?uid=3&access_token=123&op=1
	 */
	public function editProfile(){
	
	}
}
