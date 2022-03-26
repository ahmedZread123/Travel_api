<?php

namespace App\Http\Controllers;

use App\Jobs\savecity_jop;
use App\Models\city;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * profile
     * lang
     * login with facebook and google and appale
     * notification
     * fliters
     * travel
     * message validate
     * email verify
     * chat
     * seting
     */


     // get city in api
        public function get_city(){

          $city = Http::get('https://countriesnow.space/api/v0.1/countries/population/cities?fbclid=IwAR01Bu42ok9AmM7INs9g7TOd0LfLeC-gJ3AcwlnQRCrVMeywvhn6ZaRnFvg')->json();

         foreach($city['data'] as $key => $c){
             city::create([
                'city' => $c['city'],

             ]);
            //  $cities[] = $c['city'];
         }
         return  'Yes' ;
        }
}
