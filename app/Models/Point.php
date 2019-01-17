<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    public $Lat = 0;
    public $Lng = 0;

    function __construct($lng,$lat)
    {
        $this->Lng = $lng;
        $this->Lat = $lat;
    }

}
