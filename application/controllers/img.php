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
        $this->load->model('Img_model');
    }


    /**
     * 上传图片
     */
    public function upload(){

        parent::_validateToken();

        $uid = $this->input->get_post("uid");
        $ext = $this->input->get_post("ext");
        $type = $this->input->get_post("type");

        $img_path = $this->Img_model->checkUploadPath($uid, $type, $ext);
        if(empty($img_path)){
            tkProcessError("20001");
        }

        $config['upload_path'] = $img_path;
        $config['allowed_types'] = 'jpg|png';
        $config['encrypt_name'] = true;
        //$config['max_size'] = '100';
        //$config['max_width']  = '1024';
        //$config['max_height']  = '768';

        $this->load->library('upload', $config);


        if ( ! $this->upload->do_upload("img")){
            //$error = $this->upload->display_errors();

            tkProcessError("20002");
        }else{
            $data = $this->upload->data();

            //处理图片
            $img_res = $this->Img_model->processImg($data,$uid, $type, $ext);

            echo json_encode($img_res);
        }





    }


}
