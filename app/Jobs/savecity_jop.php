<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\city;
class savecity_jop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $city = Http::get('https://countriesnow.space/api/v0.1/countries/population/cities?fbclid=IwAR01Bu42ok9AmM7INs9g7TOd0LfLeC-gJ3AcwlnQRCrVMeywvhn6ZaRnFvg')->json();

        foreach($city['data'] as $key => $c){
            city::create([
               'city' => $c['city'],

            ]);
            $cities[] = $c['city'];
        }


        return 'Yes';
    }
}
