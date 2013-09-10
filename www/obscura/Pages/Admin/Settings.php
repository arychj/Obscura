<?php
	require_once(dirname(__FILE__) . '/../../PackageManager.php');
	PackageManager::Import('Core.Web.Security');
	PackageManager::Import('Core.Web.TemplateManager');

	$security = new Security();
	$security->Authorize();

	$template = new TemplateManager('Admin');

	$vars = array();
	$vars['settings-optionsList'] = '';

	$vars['settings-optionsList'] .= "<option value =\"-1\">-- New --</option>\n";
	foreach(Settings::All() as $setting)
		$vars['settings-optionsList'] .= "<option value =\"{$setting->Id}\">{$setting->Name}</option>\n";
	
	$template->Write('Settings', $vars);
?>
