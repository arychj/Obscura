<?php
	require_once(dirname(__FILE__) . '/../../PackageManager.php');
	PackageManager::Import('Core.Web.Security');
	PackageManager::Import('Core.Web.TemplateManager');
	PackageManager::Import('Core.Entities.Image');
	PackageManager::Import('Core.Entities.EntityCollection');

	$security = new Security();
	$security->Authorize();

	$template = new TemplateManager('Admin');

	if(isset($_GET['id']) && is_numeric($_GET['id'])){
		$image = Image::Retrieve($_GET['id']);
		$vars = $image->Vars;
	}
	else{
		$vars = array(
			'title' => '',
			'description' => '',
			'tags' => ''
		);
	}

	$vars['photos-optionList'] = "<option value = \"\">&nbsp;</option>\n";

	$photos = EntityCollection::All('Photo');
	foreach($photos->Members as $photo)
		$vars['photos-optionList'] .= "<option value = \"{$photo->Id}\">{$photo->Title}</option>\n";

	$template->Write('Images', $vars);
?>
