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
	 *	Obscura.Core.Entities.Entity
	 *	The base Obscura Entity object
	 *
	 *	@changelog
	 *	2013.08.30
	 *		Created
	 */

	PackageManager::Import('Core.Common.AccessorClass');
	PackageManager::Import('Core.Common.Database');
	PackageManager::Import('Core.Common.DateTimeSet');
	PackageManager::Import('Core.Common.Exceptions.EntityException');
	PackageManager::Import('Core.Entities.EntityTypes');
	PackageManager::Import('Core.Entities.Shelf');
	PackageManager::Import('Core.Entities.TagCollection');
	PackageManager::Import('Core.Entities.Url');

	class Entity extends AccessorClass{
		private $loaded = false;

		protected $id;

		private $typeid, $type;
		private $title, $description;
		private $dates;
		private $active;
		private $hitcount;
		private $url;
		private $tags;

		/*** accessors ***/

		protected function get_Id(){
			$this->Load();
			return $this->id;
		}

		protected function get_IsActive(){
			$this->Load();
			return $this->active;
		}

		protected function get_Type(){
			$this->Load();
			return $this->type;
		}

		protected function get_TypeId(){
			$this->Load();
			return $this->typeid;
		}

		protected function get_HitCount(){
			$this->Load();
			return $this->hitcount;
		}

		protected function get_Title(){
			$this->Load();
			return $this->title;
		}

		protected function get_Description(){
			$this->Load();
			return $this->description;
		}

		protected function get_Url(){
			$this->Load();

			if($this->url == null)
				$this->url = new Url($this);

			return $this->url;
		}

		protected function get_Dates(){
			$this->Load();
			return $this->dates;
		}

		protected function get_Vars(){
			$this->Load();
			return array(
				'id'			=> $this->id,
				'title'			=> $this->title,
				'description'	=> $this->description,
				'hitcount'		=> $this->hitCount,
				'tags'			=> $this->tags->Collection,
				'url'			=> "{$this->Url}",
				'dates'			=> $this->dates->Vars,
				'active'		=> $this->active
			);
		}

		protected function get_ShortVars(){
			$this->Load();
			$vars =  array(
				'id'			=> $this->id,
				'url'			=> "{$this->Url}"
			);

			if(strlen($this->title) > 0)
				$vars['title'] = $this->title;

			if(strlen($this->title) > 0)
				$vars['description'] = $this->description;

			return $vars;
		}

		protected function get_Tags(){
			$this->Load();
			return $this->tags;
		}

		protected function set_IsActive($value){
			$this->Load();
			$this->Update(null, null, $value);
			$this->active = $value;
		}

		protected function set_Title($value){
			$this->Load();
			$this->Update($value, null, null);
			$this->title = $value;
		}

		protected function set_Description($value){
			$this->Load();
			$this->Update(null, $value, null);
			$this->description = $value;
		}

		/*** end accessors ***/

		protected function __construct($id, $loadImmediately = false, $entity = null){
			$this->id = $id;

			if($loadImmediately)
				$this->Load();
			elseif($entity != null){
				$this->id			= $entity->id;
				$this->type			= $entity->type;
				$this->title		= $entity->title;
				$this->description	= $entity->description;
				$this->hitcount		= $entity->hitcount;
				$this->dates		= $entity->dates;
				$this->active		= $entity->active;

				$this->loaded = true;
			}
		}

		public function Hit(){
			$this->Load();

			$sth = Database::Prepare("UPDATE tblEntities SET hits = hits + 1 WHERE id = :id");
			$sth->bindValue('id', $this->id, PDO::PARAM_INT);
			$sth->execute();

			$this->hitcount++;
		}

		public function Update($title, $description, $active){
			$this->Load();

			$sth = Database::Prepare("UPDATE tblEntities SET title = :title, description = :description, tfActive = :active WHERE id = :id");
			$sth->bindValue('id', $this->id, PDO::PARAM_INT);
			$sth->bindValue('title', ($title == null ? $this->title : $title), PDO::PARAM_STR);
			$sth->bindValue('description', ($description == null ? $this->description : $description), PDO::PARAM_STR);
			$sth->bindValue('active', ($active == null ? $this->active : $active), PDO::PARAM_BOOL);
			
			if($sth->execute()){
				if($title != null)
					$this->title = $title;
				if($description != null)
					$this->description = $description;
				if($active != null)
					$this->active = $active;
			}
			else
				throw new EntityException("Error updating Entity Id: {$this->id}. ({$sth->errorCode()})");
		}

		public function Delete(){
			$this->Load();

			$sth = Database::Prepare("DELETE FROM tblEntities WHERE id = :id");
			$sth->bindParam('id', $this->id, $this->id, PDO::PARAM_INT);

			if(!$sth->execute())
				throw new EntityException("Error deleting Entity Id: {$this->id}");
			else if($sth->rowCount() == 0)
				throw new EntityException("Error deleting Entity Id: {$this->id}. Entity does not exist");
		}

		public function ToXml(){
			$xml = new SimpleXMLElement("<{$this->Type}/>");
			foreach($this->Vars as $var => $val)
				$xml->addChild($var, $val);

			print $xml->asXML();
		}

		public function ToJson($vars = null){
			return str_replace('\/', '/', json_encode($this->Vars));
		}
		
		private function Load(){
			if(!$this->loaded){
				$sthEntity = Database::Prepare("SELECT id, TypeId, Type, Title, Description, Hits, CreatedOn, ModifiedOn, IsActive FROM vwEntities WHERE id = :id AND IsActive = 1");
				$sthEntity->bindValue('id', $this->id, PDO::PARAM_INT);
				$sthEntity->execute();

				if(($details = $sthEntity->fetch()) != null){
					$this->typeid = $details->TypeId;
					$this->type = $details->Type;
					$this->title = $details->Title;
					$this->description = $details->Description;
					$this->hitcount = $details->Hits;
					$this->dates = new DateTimeSet($details->CreatedOn, $details->ModifiedOn);
					$this->active = $details->IsActive;

					$this->tags = new TagCollection($this->id);

					$this->loaded = true;
				}
				else{
					throw new EntityException("Invalid Entity Id: {$this->id}");
				}
			}
		}

		public static function Create($type, $title, $description){
			$sth = Database::Prepare("INSERT INTO tblEntities (id_type, title, description, dtCreated) VALUES ((SELECT id FROM tblEntityTypes WHERE name = :type), :title, :description, now())");
			$sth->bindValue('type', $type, PDO::PARAM_STR);
			$sth->bindValue('title', $title, PDO::PARAM_STR);
			$sth->bindValue('description', $description, PDO::PARAM_STR);

			if($sth->execute()){
				$entity = new Entity(Database::LastInsertId());
				$entity->type = $type;
				$entity->title = $title;
				$entity->description = $description;
				$entity->dates = new DateTimeSet(time(), time());
				$entity->active = true;

				return $entity;
			}
			else
				throw new EntityException("Unable to create Entity.");
		}

		public static function Build($id, $typeid, $type, $title, $description, $hitcount, $dtCreated, $dtModified, $active){
			$entity = new Entity($id, false);

			$entity->typeid = $typeid;
			$entity->type = $type;
			$entity->title = $title;
			$entity->description = $description;
			$entity->hitcount = $hitcount;
			$entity->dates = new DateTimeSet($dtCreated, $dtModified);
			$entity->active = $active;
			$entity->loaded = true;

			return $entity;
		}

		protected static function Retrieve($id, $loadImmediately = false){
			$entity = Shelf::Unshelve(ShelfType::Entity, $id);

			if($entity == null){
				$entity = new Entity($id, $loadImmediately);
				Shelf::Shelve(ShelfType::Entity, $id, $entity);
			}

			return $entity;
		}
	}

?>
