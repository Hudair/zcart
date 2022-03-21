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
 namespace App\Http\Controllers\Installer\Helpers; class PermissionsChecker { protected $results = []; public function __construct() { $this->results["\160\x65\162\x6d\x69\x73\x73\151\157\x6e\163"] = []; $this->results["\145\x72\x72\x6f\162\163"] = null; } public function check(array $folders) { foreach ($folders as $folder => $permission) { if (!($this->getPermission($folder) >= $permission)) { goto Rp750; } $this->addFile($folder, $permission, true); goto KJDWv; Rp750: $this->addFileAndSetErrors($folder, $permission, false); KJDWv: a85Wo: } q5EwF: return $this->results; } private function getPermission($folder) { return substr(sprintf("\45\157", fileperms(base_path($folder))), -4); } private function addFile($folder, $permission, $isSet) { array_push($this->results["\160\x65\x72\155\x69\x73\x73\x69\x6f\x6e\163"], ["\146\x6f\154\x64\145\x72" => $folder, "\160\x65\x72\x6d\151\163\163\x69\x6f\156" => $permission, "\x69\163\123\145\x74" => $isSet]); } private function addFileAndSetErrors($folder, $permission, $isSet) { $this->addFile($folder, $permission, $isSet); $this->results["\145\x72\x72\157\x72\x73"] = true; } }
