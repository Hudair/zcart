<?php

namespace App\Http\Controllers\Admin;

use Artisan;
use App\System;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Incevio extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // Restricted on demo
        if (config('app.demo') == true) {
        	echo trans('messages.demo_restriction'); exit();
        }
    }

    /**
     * Check different type system information
     */
	public function check($option = 'version')
	{
		if ($option == 'geoip' || $option == 'ip') {
            return geoip(get_visitor_IP())->toArray();
        }

        return '<h1 style="margin-top:100px; text-align: center;">You\'re markerplace running on zCart version: ' . System::VERSION . '</h1>';
	}

    /**
     * New version upgrade
     */
	public function upgrade($option = 'migrate')
	{
        Artisan::call('migrate');

        return '<info>✔</info> '. Artisan::output() .'<br/>';
	}

    /**
     * Run Artisan command
     */
	public function command($option = 'job')
	{
        if ($option == 'job') {
            Artisan::call('queue:work');
            return '<info>✔</info> '. Artisan::output() .'<br/>';
        }

        return 'Invalid command!';
	}

    /**
     * Clear config. cache etc
     */
	public function clear($all = false)
	{
        Artisan::call('optimize:clear');
    	$out = '<info>✔</info> '. Artisan::output() .'<br/><br/>';

        if ($all) {
            Artisan::call('incevio:clear-cache');
        	$out .= '<info>✔</info> '. Artisan::output() .'<br/><br/>';

            Artisan::call('cache:clear');
            $out .= '<info>✔</info> '. Artisan::output() .'<br/><br/>';
        }

        Artisan::call('incevio:boost');
        $out .= Artisan::output() .'<br/><br/>';

        return $out . '<h3 style="text-align: center;"><a href="' . url()->previous() . '">' . trans('app.back') . '</a></h3>';
	}


    /**
     * Re index scout indexing
     */
    // public function scout($model = Null)
    // {
    //     if (! $model) {
    //         return trans('app.which_model_you_want_to_reindex');
    //     }

    //     $out = '';

    //     if ($model == 'products' || $model == 'all') {
    //         Artisan::call('scout:import "App\Product"');
    //         $out .= '<info>✔</info> '. Artisan::output() .'<br/>';
    //     }

    //     if ($model == 'inventories' || $model == 'all') {
    //         Artisan::call('scout:import "App\Inventory"');
    //         $out .= '<info>✔</info> '. Artisan::output() .'<br/>';
    //     }

    //     if ($model == 'customers' || $model == 'all') {
    //         Artisan::call('scout:import "App\Customer"');
    //         $out .= '<info>✔</info> '. Artisan::output() .'<br/>';
    //     }

    //    return $out;
    // }
}
