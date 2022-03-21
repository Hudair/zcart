<?php

use Carbon\Carbon;

class PackagingsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packagings')->insert([
            'id' => 1,
            'shop_id' => NULL,
            'name' => 'Free Basic Packaging',
            'cost' => 0,
            'active' => 1,
            'created_at' => Carbon::Now(),
            'updated_at' => Carbon::Now(),
        ]);
    }
}
