<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use JsonMachine\Items;

class CountryStateCitySeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        // 1. Disable foreign key constraints
        Schema::disableForeignKeyConstraints();

        $file = public_path('assets/file/countries-states-cities.json');
        $data = Items::fromFile($file);

        $cn = 1;
        $sn = 1;
        $ctn = 1;

        $countryBatch = [];
        $stateBatch = [];
        $cityBatch = [];

        foreach ($data as $country) {

            $countryBatch[] = [
                'id' => $cn,
                'name' => $country->name,
                'iso2' => $country->iso2,
                'iso3' => $country->iso3,
                'phone_code' => $country->phonecode,
                'currency_code' => $country->currency,
                'currency_name' => $country->currency_name,
                'currency_symbol' => $country->currency_symbol,
            ];

            if (count($countryBatch) >= 500) {
                Country::insert($countryBatch);
                $countryBatch = [];
            }

            foreach ($country->states as $state) {

                $stateBatch[] = [
                    'id' => $sn,
                    'country_id' => $cn,
                    'name' => $state->name,
                    'iso2' => $state->iso2,
                    'iso3' => $state->iso3166_2
                ];

                if (count($stateBatch) >= 500) {
                    State::insert($stateBatch);
                    $stateBatch = [];
                }

                foreach ($state->cities as $city) {

                    $cityBatch[] = [
                        'id' => $ctn,
                        'state_id' => $sn,
                        'name' => $city->name
                    ];

                    if (count($cityBatch) >= 1000) {
                        City::insert($cityBatch);
                        $cityBatch = [];
                    }

                    $ctn++;
                }

                $sn++;
            }

            $cn++;
        }

        // Insert remaining
        if (!empty($countryBatch)) Country::insert($countryBatch);
        if (!empty($stateBatch)) State::insert($stateBatch);
        if (!empty($cityBatch)) City::insert($cityBatch);

        Schema::enableForeignKeyConstraints();

        echo "Done";
    }
}
