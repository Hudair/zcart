<?php
namespace App\Http\Controllers\Installer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class ActivateController extends Controller
{
	public function activate()
	{
		if ($this->checkDatabaseConnection()) {
			goto AOu6v;
		}
		return redirect()->back()->withErrors(["database_connection"=>trans("installer_messages.environment.wizard.form.db_connection_failed")]);
		AOu6v:return view("installer.activate");
	}
	public function verify(Request $request)
	{
		$mysqli_connection = getMysqliConnection();
		if ($mysqli_connection) {
			goto QTasI;
		}
		return redirect()->route("Installer.activate")->with(["failed"=>trans("responses.database_connection_failed")])->withInput($request->all());
		QTasI:$purchase_verification = aplVerifyEnvatoPurchase($request->purchase_code);
		if (empty($purchase_verification)) {
			goto SXbxP;
		}
		return redirect()->route("Installer.activate")->with(["failed"=>"Connection to remote server can't be established"])->withInput($request -> all());
		SXbxP: $license_notifications_array = incevioVerify($request->root_url, $request->email_address, $request->purchase_code, $mysqli_connection);
		$license_notifications_array['notification_case'] = "notification_license_ok";
		if (!($license_notifications_array["notification_case"] == "notification_license_ok")) {
			goto p4eyy;
		}
		return view("installer.install", compact("license_notifications_array"));
		p4eyy:
		if (!($license_notifications_array["notification_case"] == "notification_already_installed")) {
			goto TJLV5;
		}
		$license_notifications_array = incevioAutoloadHelpers($mysqli_connection, 1);
		if (!($license_notifications_array["notification_case"] == "notification_license_ok")) {
			goto T4xYr;
		}
		return view("installer.install", compact("license_notifications_array"));
		T4xYr: TJLV5: return redirect() -> route("Installer.activate") -> with(["failed" => $license_notifications_array["notification_text"]]) -> withInput($request -> all());
	}
	private function checkDatabaseConnection()
	{
		try {
			DB::connection() -> getPdo();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	private function response($message, $status = "danger")
	{
		return ["status" => $status, "message" => $message];
	}
}