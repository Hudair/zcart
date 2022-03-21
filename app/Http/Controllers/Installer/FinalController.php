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
 namespace App\Http\Controllers\Installer; use Illuminate\Routing\Controller; use App\Http\Controllers\Installer\Helpers\DatabaseManager; use App\Http\Controllers\Installer\Helpers\EnvironmentManager; use App\Http\Controllers\Installer\Helpers\FinalInstallManager; use App\Http\Controllers\Installer\Helpers\InstalledFileManager; class FinalController extends Controller { public function final(FinalInstallManager $finalInstall, EnvironmentManager $environment) { $finalMessages = $finalInstall->runFinal(); $finalEnvFile = $environment->getEnvContent(); return view("\151\156\163\164\141\x6c\154\145\x72\56\x66\x69\156\x69\x73\x68\x65\144", compact("\146\x69\x6e\141\154\x4d\145\x73\163\141\147\145\x73", "\x66\151\x6e\141\x6c\x45\x6e\166\106\x69\154\145")); } public function seedDemo(DatabaseManager $databaseManager) { $response = $databaseManager->seedDemoData(); return redirect()->route("\111\x6e\163\164\141\x6c\154\x65\x72\x2e\x66\151\x6e\x69\163\x68"); } public function finish(InstalledFileManager $fileManager) { $finalStatusMessage = $fileManager->update(); return redirect()->to(config("\x69\x6e\x73\x74\141\x6c\x6c\145\162\56\162\145\144\x69\162\x65\143\164\x55\x72\154"))->with("\x6d\x65\x73\163\141\x67\x65", $finalStatusMessage); } }
