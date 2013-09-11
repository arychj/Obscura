<?php
	require_once('obscura/PackageManager.php');
	PackageManager::Import('Core.Entities.Set');

	$set = Set::Retrieve($_GET['id']);
	$set->Hit();
?>
