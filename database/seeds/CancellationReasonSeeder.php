<?php

class CancellationReasonSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cancellation_reasons')->insert([
            [
                'detail' => 'Order created by mistake',
                'office_use' => Null,
            ], [
                'detail' => 'Delivery takes so long',
                'office_use' => Null,
            ], [
                'detail' => 'High shipping cost',
                'office_use' => Null,
            ], [
                'detail' => 'Wrong shipping address',
                'office_use' => Null,
            ], [
                'detail' => 'Need some adjustment',
                'office_use' => Null,
            ], [
                'detail' => 'Wrong billing address',
                'office_use' => Null,
            ], [
                'detail' => 'Other reason',
                'office_use' => Null,
            ], [
                'detail' => 'No inventory',
                'office_use' => 1,
            ], [
                'detail' => 'Buyer cancelled',
                'office_use' => 1,
            ], [
                'detail' => 'General adjustment',
                'office_use' => 1,
            ], [
                'detail' => 'Shipping address undeliverable',
                'office_use' => 1,
            ], [
                'detail' => 'Pricing issue',
                'office_use' => 1,
            ], [
                'detail' => 'Customs problem',
                'office_use' => 1,
            ]
        ]);
    }
}
