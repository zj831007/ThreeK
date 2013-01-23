<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-1-14
 * Time: 下午10:15
 * To change this template use File | Settings | File Templates.
 */
class Img_model extends CI_Model{

    const IMG_UPLOAD_FOLDER = "imgupload";
    const UPTYPE_AVATAR = "1";  //个人头像
    const UPTYPE_GOODSPIC = "2"; //商品图片

    const AVATAR_W = 200;   //头像小图宽
    const AVATAR_H = 200;   //头像小图高
    const GOODS_PIC_W = 200; //商品小图宽
    const GOODS_PIC_H = 200; //商品小图高

    function __construct(){
        parent::__construct();

        $this->load->model('User_model');
        $this->load->model('Goods_model');
    }


    /**
     * 取得文件上传的相对路径
     * @param $uid
     * @param $type
     * @param $goods_id
     * @return string
     */
    private function _getRelativePath($uid, $type, $goods_id){

        $img_path = self::IMG_UPLOAD_FOLDER.DIRECTORY_SEPARATOR.$uid;
        if($type == self::UPTYPE_AVATAR){
            $img_path .= DIRECTORY_SEPARATOR."avatar";
        }else if($type == self::UPTYPE_GOODSPIC){
            $img_path .= DIRECTORY_SEPARATOR."goods".$goods_id;
        }
        return $img_path;
    }

    /**
     * 检查文件上传目录是否存在，不存在就创建
     * @param $uid
     * @param $type
     * @param $ext
     * @return string
     */
     function checkUploadPath($uid, $type, $ext){
        $img_path = $this->config->item("img_upload_path");
        $img_path .= self::_getRelativePath($uid, $type, $ext);

        if(!is_dir($img_path)){
            mkdir($img_path, 0777,true);
        }

        return $img_path;
    }

    /**
     * 处理上传后的图片，生成小图
     * @param $uid
     * @param $type
     * @param $ext
     */
     function processImg($data, $uid, $type, $goods_id){
        $file_name = self::_getRelativePath($uid, $type, $goods_id).DIRECTORY_SEPARATOR.$data["file_name"];
        $file_name_small = self::_getRelativePath($uid, $type, $goods_id).DIRECTORY_SEPARATOR."small_".$data["file_name"];
        $img_path = $this->config->item("img_upload_path");
        $img_base_path = $this->config->item("img_upload_base_path");

        switch($type){
            case self::UPTYPE_AVATAR:
                $width = self::AVATAR_W;
                $height = self::AVATAR_H;
                break;
            case self::UPTYPE_GOODSPIC:
                $width = self::GOODS_PIC_W;
                $height= self::GOODS_PIC_H;
                break;
        }


        if($data["file_type"] == "image/jpeg"){
            $src_image = imagecreatefromjpeg($img_path.$file_name);
            $srcW = imagesx($src_image);
            $srcH = imagesy($src_image);

            $dst_image = imagecreatetruecolor($width, $height);
            imagecopyresized($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $srcW, $srcH);
            imagejpeg($dst_image, $img_path.$file_name_small);
            imagedestroy($src_image);
            imagedestroy($dst_image);

        }else if($data["file_type"] == "image/png"){
            $src_image = imagecreatefrompng($img_path.$file_name);
            $srcW = imagesx($src_image);
            $srcH = imagesy($src_image);

            $dst_image = imagecreatetruecolor($width, $height);
            imagecopyresized($dst_image, $src_image, 0, 0, 0, 0, $width, $height, $srcW, $srcH);
            imagepng($dst_image, $img_path.$file_name_small);
            imagedestroy($src_image);
            imagedestroy($dst_image);

        }

        //处理数据库更新
        if(file_exists($img_path.$file_name) && file_exists($img_path.$file_name_small)){

            switch($type){
                case self::UPTYPE_AVATAR:
                    //删除老图片
                    $userInfo = $this->User_model->getUserInfo($uid);
                    if(!empty($userInfo) && !empty($userInfo["icon"]) && !empty($userInfo["avatar_orgin"])){
                        if(file_exists($img_path.$userInfo["icon"])){
                            $status = unlink($img_path.$userInfo["icon"]);
                            //删除成功返回true
                        }
                        if(file_exists($img_path.$userInfo["avatar_orgin"])){
                            $status = unlink($img_path.$userInfo["avatar_orgin"]);
                        }
                    }
                    //更新新图片到数据库
                    $this->User_model->updateUserInfo($uid, $file_name_small,$file_name);

                    break;
                case self::UPTYPE_GOODSPIC:

                    //删除老图片
                    $goodsPicInfo = $this->Goods_model->getGoodsPics($uid);
                    if(!empty($goodsPicInfo) && !empty($goodsPicInfo["smallpic"]) && !empty($goodsPicInfo["bigpic"])){
                        if(file_exists($img_path.$goodsPicInfo["smallpic"])){
                            $status = unlink($img_path.$goodsPicInfo["smallpic"]);
                            //删除成功返回true
                        }
                        if(file_exists($img_path.$goodsPicInfo["bigpic"])){
                            $status = unlink($img_path.$goodsPicInfo["bigpic"]);
                        }
                    }
                    //更新新图片到数据库
                    $this->Goods_model->replaceGoodsPic($goods_id, $file_name, $file_name_small);
                    break;
            }

        }

        //返回上传后图片相对路径
        $ret = array(
            "uid" =>$uid,
            "origin_img" => $img_base_path.$file_name,
            "small_img"  => $img_base_path.$file_name_small
        );

        return $ret;
    }




}
