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
    PackageManager::Import('Core.Entities.Entity');
    PackageManager::Import('Core.Entities.EntityCollection');
    PackageManager::Import('Core.Entities.Image');

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

		protected function get_Vars(){
			$this->Load();
			return array_merge(
				array(
					'photo'			=> $this->Photo->ShortVars,
					'thumbnail'		=> $this->Thumbnail->ShortVars,
					'resolutions'	=> $this->Resolutions->Vars
				),
				parent::get_Vars()
			);
		}

		protected function set_Photo($value){
			$this->Load();
			$this->Update(null, null, $value, null, null);
			$this->photo = $value;
		}

		protected function set_Thumbnail($value){
			$this->Load();
			$this->Update(null, null, null, $value, null);
			$this->thumbnail = $value;
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

		private function Load(){
			if(!$this->loaded){
				$sth = Database::Prepare("SELECT id_photo, id_thumbnail FROM tblPhotos where id_entity = :id");
				$sth->bindValue('id', $this->Id, PDO::PARAM_INT);
				$sth->execute();

				if(($details = $sth->fetch()) != null){
					$this->photo = Image::Retrieve($details->id_photo);
					$this->thumbnail = Image::Retrieve($details->id_thumbnail);
					$this->resolutions = EntityCollection::Retrieve($this->id, EntityTypes::Image);

					$this->loaded = true;
				}
				else{
					throw new EntityException("Invalid Photo Id: {$this->id}");
				}
			}
		}

		public function Update($title, $description, $mainphoto, $thumbnail, $active){
			$this->Load();
			parent::Update($title, $description, $active);

			$sth = Database::Prepare("UPDATE tblPhotos SET id_photo = :id_photo, id_thumbnail = :id_thumbnail WHERE id_entity = :id_entity");
			$sth->bindValue('id_entity', $this->Id, PDO::PARAM_INT);
			$sth->bindValue('id_photo', ($mainphoto == null ? $this->photo->Id : $mainphoto->Id), PDO::PARAM_INT);
			$sth->bindValue('id_thumbnail', ($thumbnail == null ? $this->thumbnail->Id : $thumbnail->Id), PDO::PARAM_INT);
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
