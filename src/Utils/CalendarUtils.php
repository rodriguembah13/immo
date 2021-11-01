<?php


namespace App\Utils;


class CalendarUtils
{
function getListdays(){
    $number_days_inmonth=cal_days_in_month(CAL_GREGORIAN,2,2021);
}
}