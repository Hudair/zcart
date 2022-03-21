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
 namespace App\Http\Controllers\Installer; use Illuminate\Routing\Controller; use App\Http\Controllers\Installer\Helpers\RequirementsChecker; class RequirementsController extends Controller { protected $requirements; public function __construct(RequirementsChecker $checker) { $this->requirements = $checker; } public function requirements() { $phpSupportInfo = $this->requirements->checkPHPversion(config("\151\156\x73\x74\x61\x6c\x6c\145\162\56\143\x6f\x72\x65\x2e\x6d\x69\156\x50\x68\160\x56\x65\x72\x73\151\157\156"), config("\x69\156\163\164\141\x6c\x6c\x65\162\56\x63\x6f\162\x65\x2e\155\x61\x78\120\150\160\x56\145\162\163\151\x6f\x6e")); $requirements = $this->requirements->check(config("\151\x6e\x73\164\x61\154\154\145\162\x2e\162\145\161\165\151\162\x65\x6d\145\156\164\x73")); return view("\151\156\163\x74\141\x6c\x6c\x65\x72\56\x72\x65\x71\165\x69\x72\x65\x6d\145\x6e\164\x73", compact("\x72\x65\161\165\151\162\x65\155\x65\x6e\x74\x73", "\x70\x68\160\123\x75\x70\160\157\162\x74\x49\x6e\x66\x6f")); } }
