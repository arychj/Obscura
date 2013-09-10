<?php
	require_once(dirname(__FILE__) . '/../../PackageManager.php');
	PackageManager::Import('Core.Web.Security');
	PackageManager::Import('Core.Web.TemplateManager');

	$security = new Security();
	$security->Authorize();

	$template = new TemplateManager('Admin');

	$template->Write('Home');
?>
