<?php
/*   __________________________________________________
    |  Obfuscated by YAK Pro - Php Obfuscator  2.0.1   |
    |              on 2021-07-02 08:41:25              |
    |    GitHub: https://github.com/pk-fr/yakpro-po    |
    |__________________________________________________|
*/
/*
* Copyright (C) Incevio Systems, Inc - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Munna Khan <help.zcart@gmail.com>, September 2018
*/
 namespace App\Http\Controllers\Installer\Helpers; use Exception; use Illuminate\Support\Facades\Artisan; use Symfony\Component\Console\Output\BufferedOutput; class FinalInstallManager { public function runFinal() { $outputLog = new BufferedOutput(); $this->generateKey($outputLog); $this->publishVendorAssets($outputLog); return $outputLog->fetch(); } private static function generateKey($outputLog) { try { if (!config("\x69\x6e\163\x74\x61\x6c\x6c\x65\162\56\146\151\x6e\x61\154\x2e\153\x65\x79")) { goto Pgt9t; } Artisan::call("\153\x65\171\x3a\x67\x65\x6e\145\162\141\164\x65", ["\x2d\55\x66\157\x72\143\x65" => true], $outputLog); Pgt9t: } catch (Exception $e) { return static::response($e->getMessage(), $outputLog); } return $outputLog; } private static function publishVendorAssets($outputLog) { try { if (!config("\x69\x6e\163\164\141\154\x6c\x65\162\56\146\x69\x6e\x61\154\x2e\160\165\142\x6c\151\x73\x68")) { goto imYz6; } Artisan::call("\166\x65\x6e\144\x6f\162\x3a\x70\165\142\x6c\x69\163\x68", ["\x2d\55\141\x6c\154" => true], $outputLog); imYz6: } catch (Exception $e) { return static::response($e->getMessage(), $outputLog); } return $outputLog; } private static function response($message, $outputLog) { return ["\x73\164\x61\x74\x75\163" => "\x65\162\162\157\x72", "\155\145\163\163\x61\147\145" => $message, "\144\142\x4f\165\x74\160\x75\x74\114\x6f\147" => $outputLog->fetch()]; } }
