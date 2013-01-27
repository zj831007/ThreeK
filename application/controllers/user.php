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
        $this->load->model('Goods_model');
        
        $this->userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);

    }
    
    static $forbiddenWords = array(
    				"lihongming",
    				"admin",
    				"zhubajie",
    		);

    /**
     * 用户名密码校验
     * @param $username
     * @param $password
     */
    private function _validate($username, $password){
        //TODO
        if(empty($username)){
            tkProcessError("10002");
        }
        if( 0 !== preg_match('/^1[0-9]{10}$/',$username) ){
        	tkProcessError("10003");
        }
        if( 0 === preg_match('/^[a-zA-Z0-9_@\.]{4,20}$/',$username) ){
        	tkProcessError("10009");
        }
        foreach(User::$forbiddenWords as $words){
        	if( stristr($username,$words) ){
        		tkProcessError("10011");
        	}
        }
        if(empty($password)){
            tkProcessError("10004");
        }
        if( 0 === preg_match('/^[a-zA-Z0-9_]{6,18}$/',$password) ){
        	
            tkProcessError("10010");
        }
    }
    /**
     * 用户注册
     *
     */
    public function reg(){
        $username = $this->input->get_post("account");
        $password = $this->input->get_post("password");
        //效验参数：
        $this->_validate($username, $password);

        $ret = $this->User_model->insertNewUser($username, $password);
        if(isset($ret['uid'])){
        	$token = $this->User_model->genToken($ret['uid']);

            //此处加入设置用户上线状态 TODO

            //注册成功，返回token,uid
        	$info['access_token'] = $token;
        	$info['uid'] = $ret['uid'];

            echo json_encode($info);
        }else{
            if($ret["ret"] == 1){
                tkProcessError("10006");
            }else{
                tkProcessError("99999");
            }
        }
    }
    
    /** 
     * 用户登录
     *
     */
	public function login(){
		$ret = array();
		$username = $this->input->get_post("account");
		$password = $this->input->get_post("password");



		$userInfo = $this->User_model->checkUserPasswd($username,$password);
		if($userInfo){
            //TODO 保存push_token


			//login_success
			$token = $this->User_model->genToken($userInfo['id']);
            $info['access_token'] = $token;
            $info['uid'] = $userInfo['id'];
            echo json_encode($info);
		}else{
            //帐号或密码错误
            tkProcessError("10001");
		}
	}

	/**
	 * 用户注销
     *
	 */
	public function logout(){
		$uid = $this->input->get_post("uid");
		$token = $this->input->get_post("access_token");
		$userCollection = $this->mongodb->selectCollection(self::USER_COLLECTTION);
		$userCollection->remove( array("uid" => intval($uid), "access_token"=> $token));
		//操作成功：
        tkProcessError("88888");
	}

	/**
	 * 用户上线/下线
     *
	 */
	public function status(){
		$ret = array();
		$uid = $this->input->get_post("uid");
		$token = $this->input->get_post("access_token");
		$op = $this->input->get_post("op");
		
		if( $this->User_model->checkUidToken($uid, $token)){
			//验证通过
			if( 1 == $op ){
				$newdata = array('$set' => array("status" => 1));
				$ret['status'] = 1;
			}else{
				$newdata = array('$set' => array("status" => 2));
				$ret['status'] = 2;
			}
			$this->userCollection->update(array('uid'=>intval($uid),'access_token'=>$token),$newdata);

            //操作成功：
            tkProcessError("88888");
        }else{
			//操作失败,登录过期
            tkProcessError("10005");
		}

	}

	/**
	 * 获取个人信息
     *
	 */
	public function profile(){
		$uid = $this->input->get_post("uid");
		$profile = $this->User_model->getUserInfo($uid);
        //TODO 个人信息不全：｛avatar, nickname, intro, sex, goods_count, online｝
		$result = array();
		if($profile){
			$result['avatar'] = $profile['icon'];
			$result['nickname'] = $profile['nickname'];
			$result['intro'] = $profile['desc'];
			$result['sex'] = $profile['gender'];
			$result['goods_count'] = $this->Goods_model->getAllGoodsCntByUser($uid);
			$result['online'] = $this->User_model->getOnlineStatus($uid);
		}
		echo json_encode($result);
	}

	/**
	 * 修改用户信息
     *
	 */
	public function editProfile(){
		$ret = array();
		$uid = $this->input->get_post('uid');
		$token = $this->input->get_post('access_token');
		$gender = $this->input->get_post('gender');
		$desc = $this->input->get_post('desc');
		$tel = $this->input->get_post('tel');
		$email = $this->input->get_post('email');
		$nickname = $this->input->get_post('nickname');

        $push_token = $this->input->get_post("push_token"); //IOS push用


		if( $this->User_model->checkUidToken($uid, $token)){
			//验证成功
            $ret = $this->User_model->updateUserInfo($uid,"","",$gender,$desc,$tel,$email,$nickname);
            if( $push_token ){
            	$this->User_model->setUserMongoKV($uid,"push_token",$push_token);
            }
            //操作成功：
            tkProcessError("88888");
		}else{
            //操作失败,登录过期
            tkProcessError("10005");
		}
	}
}
