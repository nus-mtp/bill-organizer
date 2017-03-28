<?php

namespace App;

use \Carbon\Carbon;

class DateHelper{
    public static function firstDayOfCurrMonth(){
      $date =  new Carbon('first day of this month');
      $date->startOfday();
      return $date;
    }

    public static function lastDayOfCurrMonth(){
      $date =  new Carbon('last day of this month');
      $date->endOfday();
      return $date;
    }

    public static function lastDayOfLastMonth(){
      $date = new Carbon('last day of last month');
      $date->endOfday();
      return $date;
    }
}