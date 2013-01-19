<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/* Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:18
 * To change this template use File | Settings | File Templates.
 */
class User extends MY_Controller{

	
	const USER_COLLECTTION = 'user';
	private $userCollection = null;
    function __construct(){

        parent::__construct();
        $this->load->model('User_model');
        $this->userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);
    }

	/**
	 * 生成Token 更改为登陆状态
	 * @param unknown_type $uid
	 * @return string
	 */    	
    private function _genToken($uid){
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
    	$ret['status'] = '1';
    	$userCollection->insert($ret);
    	return $token;
    }
    
    /**
     * 验证toekn与用户名是否有效
     * @param string $uid
     * @param string $token
     * @return boolean
     */
    private function _checkUidToken($uid,$token){
    	$userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);
    	$query = array('uid' => $uid,'access_token' => $token);
    	$tmp = $userCollection->findOne($query);
    	if($tmp){
    		//验证通过
    		return true;
    	}else{
    		return false;
    	}
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
		$ret = array();
		$uid = $this->input->get_post("uid");
		$token = $this->input->get_post("access_token");
		$op = $this->input->get_post("op");
		
		if( $this->_checkUidToken($uid, $token)){
			//验证通过
			if( 1 == $op ){
				$newdata = array('$set' => array("status" => "1"));
				$ret['status'] = "1";
			}else{
				$newdata = array('$set' => array("status" => "2"));
				$ret['status'] = "2";
			}
			$this->userCollection->update(array('uid'=>$uid,'access_token'=>$token),$newdata);
		}
		else{
			$ret['status'] = "2";
		}
		echo json_encode($ret);
	}
	/**
	 * 获取个人信息
	 * 访问方法：http://localhost/threek/index.php/user/profile?uid=3
	 */
	public function profile(){
		$uid = $this->input->get_post("uid");
		$profile = $this->User_model->getUserInfo($uid);
		echo json_encode($profile);
	}
	/**
	 * 修改用户信息
	 * 访问方法：http://localhost/threek/index.php/user/editProfile?uid=3&access_token=123&gender=1&desc=desc123&icon=http://www.baidu.com&tel=1234567890&email=aa@aa.com&nickname=123
	 */
	public function editProfile(){
		$ret = array();
		$uid = $this->input->get_post('uid');
		$token = $this->input->get_post('access_token');
		$gender = $this->input->get_post('gender');
		$desc = $this->input->get_post('desc');
		$icon = $this->input->get_post('icon');
		$tel = $this->input->get_post('tel');
		$email = $this->input->get_post('email');
		$nickname = $this->input->get_post('nickname');
		if( $this->_checkUidToken($uid, $token)){
			//验证成功
			$ret = $this->User_model->updateUserInfo($uid,$gender,$desc,$icon,$tel,$email,$nickname);
		}else{
			//
		}
		echo json_encode($ret);
	}
}
