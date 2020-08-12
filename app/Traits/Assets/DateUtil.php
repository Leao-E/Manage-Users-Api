<?php

namespace App\Traits\Assets;

use Illuminate\Support\Carbon;

//Diferença do fuso horário
const BRAZIL_UTC = -3;

trait DateUtil {


    static public function isPast (Carbon $date){

        //Hora atual com o fuso horário
        $now = Carbon::now()->addHours(BRAZIL_UTC);

        if ($now > $date){
            return true;
        }
        return false;
    }

    static public function isFuture (Carbon $date){

        //Hora atual com o fuso horário
        $now = Carbon::now()->addHours(BRAZIL_UTC);

        if ($date > $now){
            return true;
        }
        return false;
    }

    static public function now (){
        return Carbon::now()->addHours(BRAZIL_UTC);
    }
}
