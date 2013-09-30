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
    PackageManager::Import('Core.Entities.ExifCollection');
    PackageManager::Import('Core.Entities.Image');

	class Photo extends Entity {
		private $loaded = false;

		private $photo, $thumbnail;
		private $exif, $resolutions;

		/*** accessors ***/

		protected function get_Exif(){
			$this->Load();
			return $this->exif;
		}

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
					'photo'			=> ($this->Photo == null ? null : $this->Photo->ShortVars),
					'thumbnail'		=> ($this->Thumbnail == null ? null : $this->Thumbnail->ShortVars),
					'resolutions'	=> $this->Resolutions->Vars,
					'exif'			=> $this->Exif->Vars
				),
				parent::get_Vars()
			);
		}

		protected function set_Thumbnail($value){
			$this->Load();
			$this->Update(null, null, $value, null);
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
					if($details->id_photo != null)
						$this->photo = Image::Retrieve($details->id_photo);
					if($details->id_thumbnail != null)
						$this->thumbnail = Image::Retrieve($details->id_thumbnail);

					$this->resolutions = EntityCollection::Retrieve($this->id, EntityTypes::Image);
					$this->exif = ExifCollection::Retrieve($this->id);

					$this->loaded = true;
				}
				else{
					throw new EntityException("Invalid Photo Id: {$this->id}");
				}
			}
		}

		public function Update($title, $description, $thumbnail, $active){
			if(get_class($thumbnail) != 'Image' && $thumbnail != null)
				throw new InvalidArgumentException();

			$this->Load();
			parent::Update($title, $description, $active);

			$sth = Database::Prepare("UPDATE tblPhotos SET id_thumbnail = :id_thumbnail WHERE id_entity = :id_entity");
			$sth->bindValue('id_entity', $this->Id, PDO::PARAM_INT);
			$sth->bindValue('id_thumbnail', ($thumbnail == null ? $this->thumbnail->Id : $thumbnail->Id), PDO::PARAM_INT);

			if($sth->execute()){
				if($thumbnail != null)
					$this->thumbnail = $thumbnail;
			}
			else
				throw new EntityException("Error updating Photo Id: {$this->id}");
		}

		public function Delete(){
			$this->Load();

			if($this->photo != null)
				$this->photo->Delete();
			if($this->thumbnail != null)
				$this->thumbnail->Delete();

			foreach($this->resolutions->Members as $member)
				$member->Delete();

			parent::Delete();
		}

		public static function Create($title, $description, $photo, $thumbnail){
			if(get_class($photo) != 'Image' && $photo != null)
				throw new InvalidArgumentException();
			elseif(get_class($thumbnail) != 'Image' && $thumbnail != null)
				throw new InvalidArgumentException();

			$entity = Entity::Create(EntityTypes::Photo, $title, $description);
			
			$sth = Database::Prepare("INSERT INTO tblPhotos (id_entity, id_photo, id_thumbnail) VALUES (:id_entity, :id_photo, :id_thumbnail)");
			$sth->bindValue('id_entity', $entity->Id, PDO::PARAM_INT);
			$sth->bindValue('id_photo', ($photo == null ? null : $photo->Id), PDO::PARAM_INT);
			$sth->bindValue('id_thumbnail', ($thumbnail == null ? null : $thumbnail->Id), PDO::PARAM_INT);
			$sth->execute();

			$photo = new Photo($entity->Id, false, $entity);
			$photo->photo = $photo;
			$photo->thumbnail = $thumbnail;

			return $photo;
		}

		public static function CreateFromFile($title, $description, $path, $mimetype = null){
			$title = preg_replace('/\\.[^.\\s]{3,4}$/', '', $title);
			$original = Image::Create($path, false, $mimetype, 'Original');

			$photo = self::Create(($original->Exif->Title == '' ? $title : $original->Exif->Title), ($original->Exif->Description == '' ? $description : $original->Exif->Description), $original, null);
			$photo->Resolutions->Add($original);
			$photo->exif = $original->Exif;
			$photo->exif->SaveToEntity($photo);

			$sth = Database::Prepare('SELECT name, longEdge, shortEdge FROM tblImageSizes WHERE tfGenerate = 1');
			$sth->execute();
			while(($size = $sth->fetch()) != null){
				$image = $original->Generate($size->longEdge, $size->shortEdge, $size->name, $photo->Title);
				$photo->Resolutions->Add($image);

				if($size->name == 'Thumbnail')
					$photo->Thumbnail = $image;
			}

			return $photo;
		}

		public static function Retrieve($id, $loadImmediately = false){
			return new Photo($id, $loadImmediately);
		}

		public static function All(){

		}
	}

?>
