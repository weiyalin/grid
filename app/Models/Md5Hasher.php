<?php
/**
 * Created by PhpStorm.
 * User: wangzhiyuan
 * Date: 15/12/8
 * Time: 下午5:28
 */
namespace App\Models;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class Md5Hasher implements HasherContract {

    public function make($value, array $options = array()) {
        /**
         * client:md5(md5(password));
         * db:md5(client.salt)
         */
        //取出salt
        $arr = explode("|@**@|",$value);
        $password = $arr[0];
        $salt = $arr[1];
        //$value = env('SALT', '').$value;
        return md5($password.$salt);
    }

    public function check($value, $hashedValue, array $options = array()) {
        return $this->make($value) === $hashedValue;
    }

    public function needsRehash($hashedValue, array $options = array()) {
        return false;
    }

}
