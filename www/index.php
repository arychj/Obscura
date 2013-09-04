<?php
	require_once('obscura/PackageManager.php');
	PackageManager::Import('Core.Entities.Entity');
	PackageManager::Import('Core.Entities.Image');
	PackageManager::Import('Core.Common.MimeType');

    $id = 2;
	$entity = Image::Retrieve($id, true);
	echo(get_class($entity) . '<br/>');
	echo($entity->Id . '<br/>');
	echo($entity->Title . '<br/>');
	$entity->Title = 'changed title ' . time();
	echo($entity->Dimensions->Height . '<br/>');
    print_r($entity->Tags->Collection);
	echo('<br/>');
	echo("$entity->Url<br/>");
	echo("{$entity->Dates->Modified}<br/>");


	$newEntity = Image::Create('/data/www/dev/obscura/www/test.jpg', 'new image', 'this is a new image');
	echo("$newEntity->Title<br/>");
	echo("{$newEntity->Dates->Modified}<br/>");
?>
