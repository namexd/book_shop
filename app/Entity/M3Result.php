<?php
/**
 * Created by PhpStorm.
 * User: xd
 * Date: 2017/9/22
 * Time: 16:59
 */
namespace App\Entity;
class M3Result{
    public $status;
    public $message;
//    public function __construct($status,$message)
//    {
//        $this->status=$status;
//        $this->message=$message;
//    }
    public function toJson()
    {
        return json_encode($this,JSON_UNESCAPED_UNICODE);
    }
}