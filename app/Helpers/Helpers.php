<?php
namespace App\Helpers;

use Carbon\Carbon;

class Helpers{
    static function timeToSec(String $time): int
    {
        return Carbon::createFromFormat('H:i:s',$time)->secondsSinceMidnight();
    }
    
    static function secToTime(int $seconds): String
    {
        return gmdate("H:i:s", $seconds);
    }
}