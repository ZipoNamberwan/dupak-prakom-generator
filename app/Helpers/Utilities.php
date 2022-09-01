<?php

namespace App\Helpers;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
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

    public static function getActivityNumber($activity)
    {
        // dd($activity->time);
        $period = Utilities::getSemesterPeriode($activity->time);
        $code = $activity->butirKegiatanDetail->code;
        $butirkegs = $activity->butirKegiatanDetail->subUnsurDetail->butirkegiatans;
        $butirkegs = $butirkegs->sort(function ($a, $b) {
            $n1 = explode('.', $a->code);
            $n2 = explode('.', $b->code);
            $n1 = (int) $n1[2];
            $n2 = (int) $n2[2];
            return $n1 > $n2;
        });
        $codeArray = array();
        $i = 1;
        foreach ($butirkegs as $b) {
            $codeArray[$b->code] = $i;
            $i++;
        }
        $totalbefore = 0;
        for ($i = 0; $i < $codeArray[$code] - 1; $i++) {
            $keys = array_keys($codeArray);
            $totalbefore = $totalbefore + DB::table(strtolower(str_replace('.', '', $keys[$i])))
                ->where('time', '>=', $period[0])->where('time', '<=', $period[1])->count();
        }
        $data = DB::table(strtolower(str_replace('.', '', $code)))->orderBy('time', 'asc')->get();
        $rownumber = 0;
        foreach ($data as $d) {
            $rownumber++;
            if ($d->id == $activity->id) {
                break;
            }
        }
        $final = $totalbefore + $rownumber;
        return $final;
    }
}
