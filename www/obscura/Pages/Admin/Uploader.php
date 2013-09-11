<?php
	/**
	 *	Obscura Photo Management System
	 *	http://www.github.com/arychj/obscura
	 *	©2013 Erik J. Olson
	 *
	 *	-----------------------------------------------------------------------------
	 *	"THE BEER-WARE LICENSE" (Revision 42):
	 *	<erikjolson@arych.com> wrote this file. As long as you retain this notice you
	 *	can do whatever you want with this stuff. If we meet some day, and you think
	 *	this stuff is worth it, you can buy me a beer in return. Erik J. Olson.
	 *	-----------------------------------------------------------------------------
	 *
	 *	Obscura.Pages.Admin.Uploader
	 *	Uploads Photos and Images
	 *
	 *	@changelog
	 *	2013.09.11
	 *		Created
	 */

	require_once(dirname(__FILE__) . '/../../PackageManager.php');
	PackageManager::Import('Core.Entities.Album');
	PackageManager::Import('Core.Entities.Image');
	PackageManager::Import('Core.Entities.Photo');
	PackageManager::Import('Core.Web.Security');

	$security = new Security();
	$security->Authorize();

	$key = 'images';

	$ids = array();
	if(isset($_FILES[$key]) && ($count = sizeof($_FILES[$key]['name'])) > 0){
		$album = (isset($_GET['Album']) ? Album::Retrieve($_GET['Album']) : null);
		$photo = (isset($_GET['Photo']) ? Photo::Retrieve($_GET['Photo']) : null);

		for($i = 0; $i < $count; $i++){
			if(isset($_GET['type']) && $_GET['type'] == 'Image'){
				$entity =Image::Create($_FILES[$key]['tmp_name'][$i], false, $_FILES[$key]['type'][$i]);
				if($photo != null)
					$photo->Resolutions->Add($entity);
			}
			else{
				$entity = Photo::CreateFromFile($_FILES[$key]['name'][$i], '', $_FILES[$key]['tmp_name'][$i], $_FILES[$key]['type'][$i]);
				if($album != null)
					$album->Photos->Add($entity);
			}

			$ids[] = array(
				'id'	=> $entity->Id,
				'title'	=> $entity->Title,
				'url'	=> $entity->Url
			);
		}
	}

	echo(str_replace('\/', '/', json_encode($ids)));
?>
