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
 namespace App\Http\Controllers\Installer; use Illuminate\Routing\Controller; use App\Http\Controllers\Installer\Helpers\InstalledFileManager; use App\Http\Controllers\Installer\Helpers\DatabaseManager; class UpdateController extends Controller { use \App\Http\Controllers\Installer\Helpers\MigrationsHelper; public function welcome() { return view("\151\x6e\x73\164\141\154\x6c\x65\162\x2e\x75\x70\144\x61\x74\x65\56\167\x65\x6c\143\157\155\145"); } public function overview() { $migrations = $this->getMigrations(); $dbMigrations = $this->getExecutedMigrations(); return view("\x69\156\163\164\x61\154\x6c\145\x72\56\165\160\144\141\x74\x65\56\x6f\166\145\x72\166\x69\145\167", ["\156\165\x6d\x62\x65\162\x4f\146\x55\160\144\x61\164\145\163\120\145\156\144\151\156\147" => count($migrations) - count($dbMigrations)]); } public function database() { $databaseManager = new DatabaseManager(); $response = $databaseManager->migrateAndSeed(); return redirect()->route("\x4c\141\x72\x61\166\x65\154\125\160\144\x61\164\145\x72\x3a\72\x66\151\156\141\154")->with(["\155\x65\163\163\x61\147\145" => $response]); } public function finish(InstalledFileManager $fileManager) { $fileManager->update(); return view("\x69\156\163\x74\x61\154\154\145\162\56\165\x70\x64\x61\164\x65\56\x66\x69\x6e\x69\163\x68\145\144"); } }
