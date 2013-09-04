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
	 *	Obscura.Core.Entities.Photo
	 *	A photo
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

    PackageManager::Import('Core.Common.Database');

	class Photo extends Entity {
		private $loaded = false;

		private $photo, $thumbnail;
		private $resolutions;

		/*** accessors ***/

		protected function get_Photo(){
			$this->Load();
			return $this->photo;
		}

		protected function get_Thumbnail(){
			$this->Load();
			return $this->thumbnail;
		}

		protected function get_Resolutions(){
			$this->Load();
			return $this->resolutions;
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

		public function ToXml(){

		}
		
		private function Load(){
			if(!$this->loaded){
				$sth = Database::Prepare("SELECT id_photo, id_thumbnail FROM tblPhotos where id_entity = :id");
				$sth->bindValue('id', $this->Id, PDO::PARAM_INT);
				$sth->execute();

				if(($details = $sth->fetch()) != null){
					$this->image = Image::Retrieve($details->id_photo);
					$this->thumbnail = Image::Retrieve$details->id_thumbnail);

					$this->loaded = true;
				}
				else{
					throw new EntityException("Invalid Photo Id: {$this->id}");
				}
			}
		}

		public static function Create($title, $description, $mainphoto, $thumbnail){
			if(get_class($mainphoto) != 'Image')
				throw new InvalidArgumentException();	
			elseif(get_class($thumbnail) != 'Image')
				throw new InvalidArgumentException();

			$entity = Entity::Create(EntityTypes::Photo, $title, $description);
			
			$sth = Database::Prepare("INSERT INTO tblPhotos (id_entity, id_photo, id_thumbnail) VALUES (:id_entity, :id_photo, :id_thumbnail)");
			$sth->bindValue('id_entity', $entity->Id, PDO::PARAM_INT);
			$sth->bindValue('id_photo', $mainphoto->Id, PDO::PARAM_INT);
			$sth->bindValue('id_thumbnail', $thumbnail->Id, PDO::PARAM_INT);

			$photo = new Photo($entity->Id, false, $entity);
			$photo->photo = $photo;
			$photo->thumbnail = $thumbnail;

			return $photo;
		}

		public static function Retrieve($id, $loadImmediately = false){
			return new Photo($id, $loadImmediately);
		}

		public static function All(){

		}
	}

?>
