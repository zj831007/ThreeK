<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/* Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:18
 * To change this template use File | Settings | File Templates.
 */
class Img extends MY_Controller{


    function __construct(){

        parent::__construct();
        $this->load->model('User_model');
    }



    /**
     * 上传图片
     */
    public function upload(){


        $access_token = $this->input->get_post("access_token");
        $uid = $this->input->get_post("uid");
        $img = $this->input->get_post("img");
        $ext = $this->input->get_post("ext");
        $type = $this->input->get_post("type");




    }


}
