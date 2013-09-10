<?
	require_once(dirname(__FILE__) . '/../../PackageManager.php');
	PackageManager::Import('Core.Web.AjaxManager');
	PackageManager::Import('Core.Web.Security');

	$security = new Security();
	$security->Authorize();

	$manager = new AjaxManager(AjaxFormats::Json, true, true);
	$manager->Process();

	echo($manager->Response);
?>
