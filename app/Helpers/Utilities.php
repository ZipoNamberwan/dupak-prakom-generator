<?php

namespace App\Helpers;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Str;

class Utilities
{
    public static function transformHTMLToWord($html)
    {
        //transform ENTER
        $html = Str::replace("\n", "<w:br/>", $html);

        //transform TAB 
        // $html = Str::replace("\t", "<w:tab/>", $html);
        $html = Str::replace("\t", "   ", $html);

        return $html;
    }

    public static function getSemesterPeriode($date)
    {
        $periode = array();

        if (date('m', strtotime($date)) >= 7) {
            $periode[] = date('Y', strtotime($date)) . '-07-01';
            $periode[] = date('Y', strtotime($date)) . '-12-31';
            $periode[] = 'Semester 2 ' . date('Y', strtotime($date));
        } else {
            $periode[] = date('Y', strtotime($date)) . '-01-01';
            $periode[] = date('Y', strtotime($date)) . '-06-30';
            $periode[] = 'Semester 1 ' . date('Y', strtotime($date));
        }

        return $periode;
    }

    public static function getAllPeriodeToDate()
    {
        $end = new DateTime(date('Y-m-d'));
        $begin = new DateTime('2022-01-01');
        $interval = DateInterval::createFromDateString('6 month');

        $period = new DatePeriod($begin, $interval, $end);

        $dupakperiod = array();

        foreach ($period as $dt) {
            $p = Utilities::getSemesterPeriode($dt->format('Y-m-d'));
            $dupakperiod[] = array('value' => $p, 'json_value' => json_encode([$p[0], $p[1]]));
        }

        return $dupakperiod;
    }
}
