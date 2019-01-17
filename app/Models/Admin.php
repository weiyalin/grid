<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Admin extends Model
{
    protected $table = 'user';
    //

    /**
     * 根据用户名 返回用户对象
     * @param $login_name 用户名
     * @return mixed
     */
    static function getCurrentUser($login_name)
    {
        return self::where('login_name',$login_name)->first();
    }

    /**
     * 根据salt生成 加密密码
     * @param $password     原密码
     * @param $salt         盐值
     * @return string       加密后密码
     */
    static function encryptPassword($password,$salt)
    {
        return md5(md5(md5($password)).$salt);
    }

}
