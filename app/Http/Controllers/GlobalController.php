<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GlobalController extends Controller
{
    public function fetchCurrentTranslatedText(){
        $json = base_path('resources/lang/' . app()->getLocale() . '.json');

        if (file_exists($json)) {
            $json = json_decode(file_get_contents($json), true);
        }else{
            $json = [];
        }

        return $json;
    }
}
