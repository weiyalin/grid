<?php
/**
 * Created by PhpStorm.
 * User: wangzhiyuan
 * Date: 15/12/8
 * Time: 下午5:30
 */
namespace App\Providers;

use App\Models\Md5Hasher;
use Illuminate\Hashing\HashServiceProvider;

class Md5HashServiceProvider extends HashServiceProvider {

//    public function boot()
//    {
//        parent::boot();
//
//        $this->app->bindShared('hash', function()
//        {
//            return new Md5Hasher();
//        });
//    }

    public function register()
    {
        $this->app->singleton('hash', function() { return new Md5Hasher(); });
    }


}