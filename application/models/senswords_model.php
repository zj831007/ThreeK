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
     * 查找敏感词
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

}
