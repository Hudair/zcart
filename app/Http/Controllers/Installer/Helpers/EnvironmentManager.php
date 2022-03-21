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
 namespace App\Http\Controllers\Installer\Helpers; use Exception; use Illuminate\Http\Request; class EnvironmentManager { private $envPath; private $envExamplePath; public function __construct() { $this->envPath = base_path("\x2e\145\x6e\x76"); $this->envExamplePath = base_path("\x2e\145\x6e\166\56\x65\170\141\155\x70\x6c\x65"); } public function getEnvContent() { if (file_exists($this->envPath)) { goto Q1glm; } if (file_exists($this->envExamplePath)) { goto CljdV; } touch($this->envPath); goto FslEj; CljdV: copy($this->envExamplePath, $this->envPath); FslEj: Q1glm: return file_get_contents($this->envPath); } public function getEnvPath() { return $this->envPath; } public function getEnvExamplePath() { return $this->envExamplePath; } public function saveFileClassic(Request $input) { $message = trans("\x69\x6e\x73\x74\141\154\x6c\145\x72\x5f\155\145\163\x73\x61\x67\x65\x73\x2e\x65\156\x76\151\162\157\156\x6d\145\156\164\x2e\x73\x75\143\x63\145\163\x73"); try { file_put_contents($this->envPath, $input->get("\x65\x6e\x76\x43\x6f\156\146\151\x67")); } catch (Exception $e) { $message = trans("\151\156\163\164\141\154\x6c\145\x72\x5f\x6d\145\163\163\x61\x67\x65\163\56\x65\x6e\x76\x69\x72\157\156\x6d\145\156\164\56\x65\x72\x72\157\162\x73"); } return $message; } }
