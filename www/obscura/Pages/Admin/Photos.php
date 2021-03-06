<?php
	require_once(dirname(__FILE__) . '/../../PackageManager.php');
	PackageManager::Import('Core.Web.Security');
	PackageManager::Import('Core.Web.TemplateManager');
	PackageManager::Import('Core.Entities.Photo');
	PackageManager::Import('Core.Entities.EntityCollection');

	$security = new Security();
	$security->Authorize();

	$template = new TemplateManager('Admin');

	if(isset($_GET['id']) && is_numeric($_GET['id'])){
		$photo = Photo::Retrieve($_GET['id']);
		$vars = $photo->Vars;
	}
	else{
		$vars = array(
			'title' => '',
			'description' => '',
			'tags' => ''
		);
	}

	$vars['collections-optionList'] = "<option value = \"\">&nbsp;</option>\n";
	$collections = EntityCollection::All('Collection');
	foreach($collections->Members as $collection)
		$vars['collections-optionList'] .= "<option value = \"{$collection->Id}\">{$collection->Title}</option>\n";

	$vars['sets-optionList'] = "<option value = \"\">&nbsp;</option>\n";
	$sets = EntityCollection::All('Set');
	foreach($sets->Members as $set)
		$vars['sets-optionList'] .= "<option value = \"{$set->Id}\">{$set->Title}</option>\n";

	$template->Write('Photos', $vars);
?>
