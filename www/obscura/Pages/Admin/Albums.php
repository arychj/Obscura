<?php
	require_once(dirname(__FILE__) . '/../../PackageManager.php');
	PackageManager::Import('Core.Web.Security');
	PackageManager::Import('Core.Web.TemplateManager');
	PackageManager::Import('Core.Entities.Album');
	PackageManager::Import('Core.Entities.EntityCollection');

	$security = new Security();
	$security->Authorize();

	$template = new TemplateManager('Admin');

	if(isset($_GET['id']) && is_numeric($_GET['id'])){
		$album = Album::Retrieve($_GET['id']);
		$album->Hit();
		$vars = $album->Vars;
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

	$template->Write('Albums', $vars);
?>
