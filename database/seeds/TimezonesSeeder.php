<?php

use Carbon\Carbon;

class TimezonesSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Get all of the timezones
        $timezones = json_decode(file_get_contents(__DIR__ . '/data/timezones.json'), true);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Timezone::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($timezones as $timezone) {
            DB::table('timezones')->insert([
                'value' => isset($timezone['value']) ? $timezone['value'] : Null,
                'abbr' => isset($timezone['abbr']) ? $timezone['abbr'] : Null,
                'offset' => isset($timezone['offset']) ? $timezone['offset'] : Null,
                'text' => isset($timezone['text']) ? $timezone['text'] : Null,
                'utc' => isset($timezone['utc']) ? $timezone['utc'] : Null,
                'dst' => isset($timezone['dst']) ? $timezone['dst'] : Null,
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ]);
        }
    }
}
