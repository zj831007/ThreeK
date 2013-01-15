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
     * 注册新用户
     * @param $username
     * @param $password
     *
     */
    function insertNewUser($username, $password){

        //防止sql注入
        $uname = $this->db->escape($username);
        $upass = $this->db->escape($password);

        //判断username是否已注册
        $sql = "select id from uuid where username = $uname limit 1";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0){
            //此用户已存在，错误处理 TODO


        }else{

            $sql = "insert into uuid set username = $uname";
            $this->db->query($sql);
            if($this->db->affected_rows()){
                $id  = $this->db->insert_id();


                //向user表中插入数据：根据$id分表插入 TODO



            }else{
                //插入失败，错误处理 TODO


            }

        }

    }


}
