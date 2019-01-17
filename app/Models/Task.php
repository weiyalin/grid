<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * 同步在线用户
     */
    public static function sync_online(){
        $now = ceil(microtime(true) * 1000);
        $interval = 5*60000;

        $r = DB::update("update grid_user set is_online=(case when update_time+$interval>=$now then 1 else 0 end)");
        $r = DB::update("update prowl_car set is_online=(case when update_time+$interval>=$now then 1 else 0 end)");

        return true;
    }
}
