<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by JetBrains PhpStorm.
 * User: justin
 * Date: 13-2-4
 * Time: 下午3:03
 * To change this template use File | Settings | File Templates.
 */

class Senswords_model extends CI_Model{


    const SENSWORDS_COLLECTION = 'senswords';

    function __construct(){
        parent::__construct();

        $this->senswordsCol = $this->mongodb->selectCollection(self::SENSWORDS_COLLECTION);
    }

    /**
     * 查找敏感词  for mongodb
     * @param $word
     */
    public function includeSenswords($word){

        $rs = $this->senswordsCol->findOne(array("sensword" => new MongoRegex("/".$word."/")));

        if(empty($rs)){
            return false;
        }else{
            return true;
        }

    }


    /**
     * 敏感词判断  for db
     * @param word
     * @return boolean
     */
    function isSensword($word){
        $word = $this->db->escape($word);
        $sql = "SELECT `id` FROM  `senswords` WHERE `sensword` like '*$word*'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }


}
