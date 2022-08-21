<?php

namespace App\Helpers;

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
}
