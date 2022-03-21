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
 namespace App\Http\Controllers\Installer; use Illuminate\Routing\Controller; use Illuminate\Http\Request; use Illuminate\Routing\Redirector; use App\Http\Controllers\Installer\Helpers\EnvironmentManager; use Validator; class EnvironmentController extends Controller { protected $EnvironmentManager; public function __construct(EnvironmentManager $environmentManager) { $this->EnvironmentManager = $environmentManager; } public function environmentMenu() { return view("\x69\x6e\163\x74\141\x6c\154\x65\162\x2e\x65\x6e\166\x69\162\157\x6e\155\x65\x6e\x74"); } public function environmentWizard() { } public function environmentClassic() { $envConfig = $this->EnvironmentManager->getEnvContent(); return view("\151\x6e\x73\164\141\x6c\x6c\x65\162\x2e\145\x6e\166\x69\x72\x6f\156\x6d\145\x6e\x74\x2d\x63\x6c\141\163\163\151\143", compact("\x65\x6e\x76\103\x6f\x6e\146\x69\147")); } public function saveClassic(Request $input, Redirector $redirect) { $message = $this->EnvironmentManager->saveFileClassic($input); return $redirect->route("\x49\156\163\x74\x61\x6c\x6c\x65\162\x2e\x65\156\166\151\162\157\x6e\x6d\x65\156\164\x43\154\x61\163\163\151\143")->with(["\155\x65\x73\163\141\x67\145" => $message]); } }
