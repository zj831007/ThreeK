<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/* Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:18
 * To change this template use File | Settings | File Templates.
 */
class User extends MY_Controller{
	

    function __construct(){

        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Goods_model');
    }

    /**
     * 用户名密码校验
     * @param $username
     * @param $password
     */
    private function _validate($username, $password){

        parent::_valideateSenswords($username);

        if(empty($username)){
            tkProcessError("10002");
        }
        if( 0 !== preg_match('/^1[0-9]{10}$/',$username) ){
        	tkProcessError("10008");
        }
        if( 0 === preg_match('/^[a-zA-Z0-9_@\.]{4,20}$/',$username) ){
        	tkProcessError("10009");
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
        	$info['uid'] = intval($ret['uid']);

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

		parent::_validateUID($uid);
        parent::_validateToken();


        $this->User_model->removeToken($uid, $token);
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

        parent::_validateUID($uid);
        parent::_validateToken();


        $this->User_model->updateOnlineStatus($uid, $token, $op);

        //操作成功：
        tkProcessError("88888");

	}

	/**
	 * 获取个人信息
     *
	 */
	public function profile(){
		$uid = $this->input->get_post("uid");

        parent::_validateUID($uid);

		$profile = $this->User_model->getUserInfo($uid);

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

        parent::_validateUID($uid);
        parent::_validateToken();

        if(!empty($nickname)){
            parent::_valideateSenswords($nickname);
        }
        if(!empty($desc)){
            parent::_valideateSenswords($desc);
        }


        $push_token = $this->input->get_post("push_token"); //IOS push用

        //验证成功
        $ret = $this->User_model->updateUserInfo($uid,"","",$gender,$desc,$tel,$email,$nickname);
        if( $push_token ){
            $this->User_model->setUserMongoKV($uid,"push_token",$push_token);
        }
        //操作成功：
        tkProcessError("88888");

	}
}
