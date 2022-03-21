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
 namespace App\Http\Controllers\Installer; use Exception; use Illuminate\Support\Facades\DB; use Illuminate\Routing\Controller; use App\Http\Controllers\Installer\Helpers\DatabaseManager; class DatabaseController extends Controller { private $databaseManager; public function __construct(DatabaseManager $databaseManager) { $this->databaseManager = $databaseManager; } public function database() { if ($this->checkDatabaseConnection()) { goto K8N_5; } return redirect()->back()->withErrors(["\x64\141\164\141\x62\x61\x73\145\x5f\x63\x6f\x6e\156\145\143\164\x69\157\156" => trans("\x69\156\x73\164\x61\154\154\x65\x72\x5f\x6d\x65\163\163\141\147\x65\163\56\x65\156\166\151\162\157\156\x6d\x65\x6e\x74\56\167\151\172\141\162\144\56\x66\x6f\162\155\x2e\144\142\x5f\x63\x6f\x6e\156\x65\143\164\x69\x6f\x6e\x5f\146\x61\x69\x6c\x65\x64")]); K8N_5: ini_set("\x6d\141\170\137\145\170\x65\x63\165\x74\151\157\x6e\137\x74\x69\x6d\x65", 600); $response = $this->databaseManager->migrateAndSeed(); return redirect()->route("\x49\x6e\x73\164\x61\x6c\x6c\145\162\56\146\151\156\141\154")->with(["\155\145\x73\163\141\x67\x65" => $response]); } private function checkDatabaseConnection() { try { DB::connection()->getPdo(); return true; } catch (Exception $e) { return false; } } }
