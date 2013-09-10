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
	 *	Obscura.Core.Entities.Image
	 *	The base Obscura Image object
	 *
	 *	@changelog
	 *	2013.09.02
	 *		Created
	 */

	PackageManager::Import('Core.Settings');
	PackageManager::Import('Core.Common.Database');
	PackageManager::Import('Core.Common.MimeType');
	PackageManager::Import('Core.Entities.Entity');
	PackageManager::Import('Core.Entities.Dimensions');
	PackageManager::Import('Core.Entities.Exif');

	class Image extends Entity {
		private $loaded = false;

		private $dimensions;
		private $exif;
		private $mimeType, $extension, $filePath, $html;

		/*** accessors ***/

		protected function get_Dimensions(){
			$this->Load();
			return $this->dimensions;
		}

		protected function get_Exif(){
			$this->Load();
			return $this->exif;
		}

		protected function get_MimeType(){
			$this->Load();
			return $this->mimeType;
		}

		protected function get_Extension(){
			$this->Load();
			return $this->extension;
		}

		protected function get_FilePath(){
			$this->Load();
			return $this->filePath;
		}

		protected function get_Url(){
			$this->Load();
			return parent::get_Url() . ".{$this->extension}";
		}

		protected function get_Html(){
			$this->Load();
			return $this->html;
		}

		protected function get_Vars(){
			return array_merge(
				array(
					'dimensions' => $this->Dimensions->Vars
				),
				parent::get_Vars()
			);
		}

		protected function get_ShortVars(){
			return array_merge(
				array(
					'dimensions' => $this->Dimensions->Vars
				),
				parent::get_ShortVars()
			);
		}

		/*** end accessors ***/

		protected function __construct($id, $loadImmediately = false, $entity = null){
			if($entity != null)
				parent::__construct($id, false, $entity);
			else{
				$this->id = $id;
				if($loadImmediately)
					$this->Load();	
			}
		}

		public function Display(){
			$imageDirectory = Settings::GetSettingValue('ImageDirectory');

			header("Content-type: {$this->MimeType}", true);
			header("Cache-Control: max-age=2592000", true); //30 days
			readfile("$imageDirectory/{$this->FilePath}");
			exit();
		}

		public function ToXml(){

		}
		
		private function Load(){
			if(!$this->loaded){
				$sth = Database::Prepare("SELECT path, mimeType, extension, width, height FROM tblImages where id_entity = :id");
				$sth->bindValue('id', $this->Id, PDO::PARAM_INT);
				$sth->execute();

				if(($image = $sth->fetch()) != null){
					$this->filePath = $image->path;
					$this->mimeType = $image->mimeType;
					$this->extension = $image->extension;
					$this->dimensions = new Dimensions($image->width, $image->height);

					$sthExif = Database::Prepare("SELECT name FROM Type, Value FROM vwImageExifData WHERE EntityId = :id_entity");
					$sthExif->bindValue('id_entity', $this->Id, PDO::PARAM_INT);
					if($sth->execute()){
						$exifTags = array();
						while(($exifTag = $sthExif->fetch()) != null)
							$exifTags[$exifTag->Type] = $exif->Value;

						$this->exif = new Exif($exifTags);
					}

					$this->loaded = true;
				}
				else{
					throw new EntityException("Invalid Image Entity Id: {$this->id}");
				}
			}
		}

		public static function Create($sourcepath){
			$entity = Entity::Create(EntityTypes::Image, '', '');

			$extension = pathinfo($sourcepath, PATHINFO_EXTENSION);
			$mimetype = MimeType::ParseExtension($extension);
			$filename = "{$entity->Id}.$extension";
			$destpath = Settings::GetSettingValue('ImageDirectory') . '/' . $filename;

			if(strtolower(Settings::GetSettingValue('ImageNewFileAction')) == 'move')
				rename($sourcepath, $destpath);
			else
				copy($sourcepath, $destpath);

			$exif =Exif::GetExif($destpath);

			$sth = Database::Prepare("INSERT INTO tblImages (id_entity, path, mimetype, extension, width, height, size) VALUES (:id_entity, :path, :mimetype, :extension, :width, :height, :size)");
			$sth->bindValue('id_entity', $entity->Id, PDO::PARAM_INT);
			$sth->bindValue('path', $filename, PDO::PARAM_STR);
			$sth->bindValue('mimetype', $mimetype, PDO::PARAM_STR);
			$sth->bindValue('extension', $extension, PDO::PARAM_STR);
			$sth->bindValue('width', $exif->Width, PDO::PARAM_INT);
			$sth->bindValue('height', $exif->Height, PDO::PARAM_INT);
			$sth->bindValue('size', filesize($destpath), PDO::PARAM_INT);
			
			if($sth->execute()){
				$image = new Image(Database::LastInsertId(), false, $entity);
				$image->filePath = $sourcepath;
				$image->mimeType = "";
				$image->dimensions = new Dimensions(0, 0);

				$exif->SaveToEntity($image);

				return $image;
			}
			else
				throw new EntityException("Unable to create Image ({$sth->errorCode()}).");
		}

		public static function Retrieve($id, $loadImmediately = false){
			return new Image($id, $loadImmediately);
		}

		public static function All(){

		}
	}

?>
