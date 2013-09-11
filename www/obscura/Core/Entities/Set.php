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
	 *	Obscura.Core.Entities.Set
	 *	<description>
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

	PackageManager::Import('Core.Common.Database');
	PackageManager::Import('Core.Entities.Entity');
	PackageManager::Import('Core.Entities.EntityCollection');
	PackageManager::Import('Core.Entities.Image');

	class Set extends Entity {
		private $loaded = false;

		private $cover, $thumbnail;
		private $photos;

		/*** accessors ***/

		protected function get_Cover(){
			$this->Load();
			return $this->cover;
		}

		protected function get_Thumbnail(){
			$this->Load();
			return $this->thumbnail;
		}

		protected function get_Photos(){
			$this->Load();
			return $this->photos;
		}

		protected function get_Vars(){
			$this->Load();
			return array_merge(array(
				'cover'		=> $this->Cover->ShortVars,
				'thumbnail'	=> $this->Thumbnail->ShortVars,
				'photos'	=> $this->Photos->Vars
			),
			parent::get_Vars());
		}

		protected function set_Cover($value){
			$this->Load();
			$this->Update(null, null, $value, null, null);
			$this->cover = $value;
		}

		protected function set_Thumbnail($value){
			$this->Load();
			$this->Update(null, null, null, $value,  null);
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

		public function Update($title, $description, $cover, $thumbnail, $active){
			if(get_class($cover) != 'Image')
				throw new InvalidArgumentException();	
			elseif(get_class($thumbnail) != 'Image')
				throw new InvalidArgumentException();

			$this->Load();
			parent::Update($title, $description, $active);

			$sth = Database::Prepare("UPDATE tblSets SET id_cover = :id_cover, id_thumbnail = :id_thumbnail WHERE id_entity = :id_entity");
			$sth->bindValue('id_entity', $this->Id, PDO::PARAM_INT);
			$sth->bindValue('id_cover', ($cover == null ? $this->cover->Id : $cover->Id), PDO::PARAM_INT);
			$sth->bindValue('id_thumbnail', ($thumbnail == null ? $this->thumbnail->Id : $thumbnail->Id), PDO::PARAM_INT);

			if($sth->execute()){
				if($cover != null)
					$this->cover = $cover;
				if($thumbnail != null)
					$this->thumbnail = $thumbnail;
			}
			else
				throw new EntityException("Error updating Set Id: {$this->id}");
		}

		private function Load(){
			if(!$this->loaded){
				$sth = Database::Prepare("SELECT id_cover, id_thumbnail FROM tblSets where id_entity = :id");
				$sth->bindValue('id', $this->Id, PDO::PARAM_INT);
				$sth->execute();

				if(($details = $sth->fetch()) != null){
					$this->cover = Image::Retrieve($details->id_cover);
					$this->thumbnail = Image::Retrieve($details->id_thumbnail);
					$this->photos = EntityCollection::Retrieve($this->id, EntityTypes::Photo);

					$this->loaded = true;
				}
				else
					throw new EntityException("Invalid Set Id: {$this->id}");
			}
		}

		public static function Create($title, $description, $cover, $thumbnail){
			if(get_class($cover) != 'Image')
				throw new InvalidArgumentException();	
			elseif(get_class($thumbnail) != 'Image')
				throw new InvalidArgumentException();

			$entity = Entity::Create(EntityTypes::Set, $title, $description);
			
			$sth = Database::Prepare("INSERT INTO tblSets (id_entity, id_cover, id_thumbnail) VALUES (:id_entity, :id_cover, :id_thumbnail)");
			$sth->bindValue('id_entity', $entity->Id, PDO::PARAM_INT);
			$sth->bindValue('id_cover', $cover->Id, PDO::PARAM_INT);
			$sth->bindValue('id_thumbnail', $thumbnail->Id, PDO::PARAM_INT);

			$set = new Set($entity->Id, false, $entity);
			$set->cover = $cover;
			$set->thumbnail = $thumbnail;

			return $set;
		}

		public static function Retrieve($id, $loadImmediately = false){
			return new Set($id, $loadImmediately);
		}

		public static function All(){

		}
	}

?>
