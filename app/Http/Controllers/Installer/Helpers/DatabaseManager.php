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
 namespace App\Http\Controllers\Installer\Helpers; use Exception; use Illuminate\Database\SQLiteConnection; use Illuminate\Support\Facades\Artisan; use Illuminate\Support\Facades\Config; use Illuminate\Support\Facades\DB; use Symfony\Component\Console\Output\BufferedOutput; class DatabaseManager { public function migrateAndSeed() { $outputLog = new BufferedOutput(); $this->sqlite($outputLog); return $this->migrate($outputLog); } private function migrate($outputLog) { try { Artisan::call("\155\151\147\x72\141\164\145", ["\x2d\55\x66\157\162\x63\x65" => true], $outputLog); } catch (Exception $e) { return $this->response($e->getMessage(), "\x65\x72\162\x6f\162", $outputLog); } return $this->seed($outputLog); } private function seed($outputLog) { try { Artisan::call("\144\142\x3a\163\x65\145\x64", ["\55\55\146\157\x72\x63\145" => true], $outputLog); } catch (Exception $e) { return $this->response($e->getMessage(), "\x65\x72\162\x6f\162", $outputLog); } return $this->response(trans("\151\x6e\x73\164\x61\x6c\x6c\145\162\137\155\145\x73\x73\141\147\x65\x73\x2e\146\x69\x6e\141\x6c\56\146\x69\x6e\x69\163\150\x65\144"), "\163\165\x63\x63\145\x73\163", $outputLog); } public function seedDemoData() { ini_set("\x6d\x61\x78\x5f\x65\170\x65\143\x75\x74\151\x6f\x6e\137\164\151\155\x65", 1200); $outputLog = new BufferedOutput(); try { Artisan::call("\151\156\143\x65\x76\151\x6f\x3a\x64\145\x6d\157"); } catch (Exception $e) { return $this->response($e->getMessage(), "\x65\x72\x72\x6f\x72", $outputLog); } return $this->response(trans("\151\x6e\x73\164\141\154\154\145\162\137\x6d\x65\163\163\141\147\x65\x73\x2e\x66\151\x6e\x61\x6c\x2e\146\x69\156\x69\x73\x68\x65\x64"), "\163\165\x63\143\x65\163\163", $outputLog); } private function response($message, $status = "\144\141\x6e\147\x65\x72", $outputLog) { return ["\163\x74\141\164\x75\x73" => $status, "\155\145\163\x73\x61\x67\x65" => $message, "\x64\x62\x4f\x75\x74\x70\x75\x74\x4c\157\x67" => $outputLog->fetch()]; } private function sqlite($outputLog) { if (!DB::connection() instanceof SQLiteConnection) { goto y4HaE; } $database = DB::connection()->getDatabaseName(); if (file_exists($database)) { goto GVswr; } touch($database); DB::reconnect(Config::get("\x64\x61\164\141\142\x61\163\145\x2e\144\145\x66\141\165\x6c\x74")); GVswr: $outputLog->write("\x55\x73\x69\x6e\x67\x20\x53\161\154\x4c\151\164\x65\40\144\x61\x74\141\x62\141\x73\145\x3a\x20" . $database, 1); y4HaE: } }