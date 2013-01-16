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
    }


    /**
     * 用户注册
     * 访问方法：http://localhost/threek/index.php/user/reg?account=3&password=eeeqr
     */
    public function reg(){

        $username = $this->input->get_post("account");
        $password = $this->input->get_post("password");

        //效验参数：TODO
        tkProcessError(0);  //错误处理方法，调用函数并传入错误码就可显示错误信息，此处为测试用。在model中也用此方法处理错误。错误码定义在custom_conf.php中

        $uuid = $this->User_model->insertNewUser($username, $password);



    }
    
    /** 
     * 用户登录
     * 访问方法：http://localhost/threek/index.php/user/login?account=3&password=eeeqr
     */
	public function login(){
		$username = $this->input->get_post("account");
		$password = $this->input->get_post("password");
		
		$userInfo = $this->User_model->checkUserPasswd($username,$password);
		if($userInfo){
			echo json_encode($userInfo);
		}else{
			echo "";
		}
	}

}
