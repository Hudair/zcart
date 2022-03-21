<?php

use Carbon\Carbon;

class StatesSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Get all of the countries
        $file_path = 'data/states';

        $files = glob(__DIR__ . '/' . $file_path . '/' . '*.json');

        foreach ($files as $file) {
            $country_code = basename($file, ".json"); // Get the the country iso_code from file name

            $country = \DB::table('countries')->where('iso_code', $country_code)->first();

            $country_id = $country->id;
            $country_name = $country->name;

            //If the $country_id not found in countries table then ignore the file and move next
            if (!$country_id) continue;

            $json = json_decode(file_get_contents($file), true);

            foreach ($json as $key => $state) {
                if (!isset($state['iso_code'])) continue;

                DB::table('states')->insert([
                    'country_id' => $country_id,
                    // 'country_name' => $country_name,
                    'name' => $state['name'],
                    'iso_code' => isset($state['iso_code']) ? $state['iso_code'] : NULL,
                    'iso_numeric' => isset($state['iso_numeric']) ? $state['iso_numeric'] : NULL,
                    // 'region' => isset($state['region']) ? $state['region'] : NULL,
                    // 'region_code' => isset($state['region_code']) ? $state['region_code'] : NULL,
                    'calling_code' => isset($state['calling_code']) ? $state['calling_code'] : NULL,
                    'active' => isset($state['active']) ? $state['active'] : 1,
                    'created_at' => Carbon::Now(),
                    'updated_at' => Carbon::Now(),
                ]);
            }
        }
    }
}
