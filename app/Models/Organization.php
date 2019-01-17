<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Organization extends Model
{
    //

    public static function get_all(){
        $list = DB::table('organization')->where('status',0)->get();
        return $list;
    }
}
