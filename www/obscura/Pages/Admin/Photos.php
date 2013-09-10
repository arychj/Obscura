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
		$photo->Hit();
		$vars = $photo->Vars;
	}
	else{
		$vars = array(
			'title' => '',
			'description' => '',
			'tags' => ''
		);
	}

	$vars['albums-optionList'] = "<option value = \"\">&nbsp;</option>\n";

	$albums = EntityCollection::All('Album');
	foreach($albums->Members as $album)
		$vars['albums-optionList'] .= "<option value = \"{$album->Id}\">{$album->Title}</option>\n";

	$template->Write('Photos', $vars);
?>
