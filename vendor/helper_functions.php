<?php
function aplCustomEncrypt($string, $key) {}
function aplCustomDecrypt($string, $key) {}
function aplValidateIntegerValue($number, $min_value = 0, $max_value = INF) {$result = false; return $result;}
function aplValidateRawDomain($url) {}
function aplGetCurrentUrl($remove_last_slash = null) {}
function aplGetRawDomain($url) {}
function aplGetRootUrl($url, $remove_scheme, $remove_www, $remove_path, $remove_last_slash) {}
function aplCustomPost($url, $post_info = null, $refer = null) {}
function aplVerifyDateTime($datetime, $format) {}
function aplGetDaysBetweenDates($date_from, $date_to) {}
function aplParseXmlTags($content, $tag_name) {}
function aplParseServerNotifications($content_array, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE, $product = Null) {}
function aplGenerateScriptSignature($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE, $product = Null) {}
function aplVerifyServerSignature($notification_server_signature, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE, $product = Null) {}
function aplCheckSettings() {}
function aplParseLicenseFile() {}
function aplGetLicenseData($MYSQLI_LINK = null) {}
function aplCheckConnection() {}
function aplCheckData($MYSQLI_LINK = null) {}
function aplVerifyEnvatoPurchase($LICENSE_CODE = null) {}
function incevioVerify($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE, $MYSQLI_LINK = null) {}
function preparePackageInstallation($installable) {}
function incevioAutoloadHelpers($MYSQLI_LINK = null, $FORCE_VERIFICATION = 0) {}
function aplVerifySupport($MYSQLI_LINK = null) {}
function aplVerifyUpdates($MYSQLI_LINK = null) {}
function incevioUpdateLicense($MYSQLI_LINK = null) {}
function incevioUninstallLicense($MYSQLI_LINK = null) {}
function aplDeleteData($MYSQLI_LINK = null) {}