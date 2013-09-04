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
	 *	Obscura.Core.Entities.Album
	 *	<description>
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

	PackageManager::Import('Core.Common.Database');

	class Album extends Entity {
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
				$sth = Database::Prepare("SELECT id_cover, id_thumbnail FROM tblAlbums where id_entity = :id");
				$sth->bindValue('id', $this->Id, PDO::PARAM_INT);
				$sth->execute();

				if(($details = $sth->fetch()) != null){
					$this->image = Image::Retrieve($details->id_cover);
					$this->thumbnail = Image::Retrieve($details->id_thumbnail);

					$this->loaded = true;
				}
				else{
					throw new EntityException("Invalid Album Id: {$this->id}");
				}
			}
		}

		public static function Create($title, $description, $cover, $thumbnail){
			if(get_class($cover) != 'Image')
				throw new InvalidArgumentException();	
			elseif(get_class($thumbnail) != 'Image')
				throw new InvalidArgumentException();

			$entity = Entity::Create(EntityTypes::Album, $title, $description);
			
			$sth = Database::Prepare("INSERT INTO tblAlbums (id_entity, id_cover, id_thumbnail) VALUES (:id_entity, :id_cover, :id_thumbnail)");
			$sth->bindValue('id_entity', $entity->Id, PDO::PARAM_INT);
			$sth->bindValue('id_cover', $cover->Id, PDO::PARAM_INT);
			$sth->bindValue('id_thumbnail', $thumbnail->Id, PDO::PARAM_INT);

			$album = new Album($entity->Id, false, $entity);
			$album->cover = $cover;
			$album->thumbnail = $thumbnail;

			return $album;
		}

		public static function Retrieve($id, $loadImmediately = false){
			return new Album($id, $loadImmediately);
		}

		public static function All(){

		}
	}

?>
