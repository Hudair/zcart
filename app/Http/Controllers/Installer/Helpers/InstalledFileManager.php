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
 namespace App\Http\Controllers\Installer\Helpers; class InstalledFileManager { public function create() { $installedLogFile = storage_path("\151\x6e\x73\x74\x61\x6c\x6c\x65\x64"); $dateStamp = date("\131\57\155\57\144\40\150\72\151\72\x73\x61"); if (!file_exists($installedLogFile)) { goto iRVpm; } $message = trans("\151\x6e\x73\x74\141\154\154\145\x72\x5f\155\145\163\163\141\x67\145\x73\x2e\165\160\x64\141\x74\145\162\56\x6c\157\x67\x2e\163\x75\143\143\145\163\x73\x5f\155\x65\163\163\x61\x67\x65") . $dateStamp; file_put_contents($installedLogFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX); goto kYWvT; iRVpm: $message = trans("\x69\x6e\163\x74\141\x6c\x6c\x65\162\137\155\145\163\x73\141\147\145\x73\x2e\151\x6e\x73\x74\141\154\x6c\x65\x64\x2e\x73\165\x63\143\145\x73\x73\x5f\x6c\x6f\147\x5f\155\x65\163\x73\x61\147\x65") . $dateStamp . "\12"; file_put_contents($installedLogFile, $message); kYWvT: return $message; } public function update() { return $this->create(); } }
