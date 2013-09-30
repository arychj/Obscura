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
	PackageManager::Import('Core.Common.Exceptions.ImageException');
	PackageManager::Import('Core.Common.MimeType');
	PackageManager::Import('Core.Entities.Entity');
	PackageManager::Import('Core.Entities.Dimensions');
	PackageManager::Import('Core.Entities.ExifCollection');

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
				parent::get_ShortVars()
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

		protected function set_Title($value){

		}

		protected function set_Description($value){

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

		public function Generate($edgeLong, $edgeShort, $type = null, $title = null){
			$destW = null;
			$destH = null;
			$srcX = null;
			$srcY = null;
			$srcW = null;
			$srcH = null;

			$imageDirectory = Settings::GetSettingValue('ImageDirectory');
			$filename = "$imageDirectory/{$this->FilePath}";
			$tempfile = tempnam(sys_get_temp_dir(), 'obscura');

			//calculate size
			list($orgW, $orgH) = getimagesize($filename);
			$ratio = $orgW / $orgH;

			if($edgeShort == null && $edgeLong != null){ //resize on long edge
				$srcX = 0;
				$srcY = 0;
				$srcW = $orgW;
				$srcH = $orgH;

				if($ratio < 1){
					$destH = $edgeLong;
					$destW = $destH * $ratio;
				}
				else{
					$destW = $edgeLong;
					$destH = $destW / $ratio;
				}
			}
			elseif($edgeShort != null && $edgeLong == null){ //resize on short edge
				$srcX = 0;
				$srcY = 0;
				$srcW = $orgW;
				$srcH = $orgH;

				if($ratio < 1){
					$destW = $edgeShort;
					$destH = $destW / $ratio;
				}
				else{
					$destH = $edgeShort;
					$destW = $destH * $ratio;
				}
			}
			elseif($edgeShort != null && $edgeLong != null){ //crop to size
				if($ratio < 1){
					$cropRatio = ($orgW / $edgeShort);
					$srcY = $orgH / $cropRatio / 2;
					$srcX = 0;
					$srcH = $edgeLong * $cropRatio;
					$srcW = $edgeShort * $cropRatio;
					$destH = $edgeLong;
					$destW = $edgeShort;
				}
				else{
					$cropRatio = ($orgH / $edgeShort);
					$srcX = $orgW / $cropRatio / 2;
					$srcY = 0;
					$srcW = $edgeLong * $cropRatio;
					$srcH = $edgeShort * $cropRatio;
					$destW = $edgeLong;
					$destH = $edgeShort;
				}
			}

			if($destW !== null && $destH !== null && $srcX !== null && $srcY !== null && $srcW !== null && $srcH != null){
				$image_p = imagecreatetruecolor($destW, $destH);
				$image = imagecreatefromjpeg($filename);
				imagecopyresampled($image_p, $image, 0, 0, $srcX, $srcY, $destW, $destH, $srcW, $srcH);
				imagejpeg($image_p, $tempfile, 100);

				$image = self::Create($tempfile, false, $this->mimeType, $type);
				$image->title = $title;

				@unlink($tempfile);

				return $image;
			}
			else{
				@unlink($tempfile);
				throw new ImageException("Unable to generate Image. Invalid dimensions specified.");
			}
		}

		public function GenerateThumbnail(){
			return $this->Generate(200, 200);
		}

		public function Delete(){
			$this->Load();
			$imageDirectory = Settings::GetSettingValue('ImageDirectory');
			$filename = "$imageDirectory/{$this->FilePath}";

			@unlink($filename);

			parent::Delete();
		}

		private function Load(){
			if(!$this->loaded){
				$sth = Database::Prepare("SELECT title, path, mimeType, extension, width, height FROM vwImages WHERE id_entity = :id");
				$sth->bindValue('id', $this->Id, PDO::PARAM_INT);
				$sth->execute();

				if(($image = $sth->fetch()) != null){
					$this->title = $image->title;
					$this->filePath = $image->path;
					$this->mimeType = $image->mimeType;
					$this->extension = $image->extension;
					$this->dimensions = new Dimensions($image->width, $image->height);
					$this->exif = ExifCollection::FromEntity($this);

					$this->loaded = true;
				}
				else{
					throw new EntityException("Invalid Image Entity Id: {$this->id}");
				}
			}
		}

		public static function Create($sourcepath, $saveExif = false, $mimetype = null, $type = null){
			$entity = Entity::Create(EntityTypes::Image, '', '');

			if($mimetype == null){
				$extension = pathinfo($sourcepath, PATHINFO_EXTENSION);
				$mimetype = MimeType::ParseExtension($extension);
			}
			else{
				$extension = MimeType::LookupExtension($mimetype);
			}

			$filename = "{$entity->Id}.$extension";
			$destpath = Settings::GetSettingValue('ImageDirectory') . '/' . $filename;

			if(strtolower(Settings::GetSettingValue('ImageNewFileAction')) == 'move')
				rename($sourcepath, $destpath);
			else
				copy($sourcepath, $destpath);

			$exif = ExifCollection::GetExif($destpath);

			$sth = Database::Prepare("INSERT INTO tblImages (id_entity, id_type, path, mimetype, extension, width, height, size) VALUES (:id_entity, (SELECT id FROM tblImageTypes WHERE name = :type), :path, :mimetype, :extension, :width, :height, :size)");
			$sth->bindValue('id_entity', $entity->Id, PDO::PARAM_INT);
			$sth->bindValue('type', $type, PDO::PARAM_STR);
			$sth->bindValue('path', $filename, PDO::PARAM_STR);
			$sth->bindValue('mimetype', $mimetype, PDO::PARAM_STR);
			$sth->bindValue('extension', $extension, PDO::PARAM_STR);
			$sth->bindValue('width', $exif->Width, PDO::PARAM_INT);
			$sth->bindValue('height', $exif->Height, PDO::PARAM_INT);
			$sth->bindValue('size', filesize($destpath), PDO::PARAM_INT);
			
			if($sth->execute()){
				$image = new Image(Database::LastInsertId(), false, $entity);
				$image->filePath = $filename;
				$image->mimeType = $mimetype;
				$image->extension = $extension;
				$image->dimensions = new Dimensions($exif->Width, $exif->Height);
				$image->exif = $exif;
				$image->loaded = true;

				if($saveExif)
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
