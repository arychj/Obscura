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
	 *	Obscura.Core.Entities.TagCollection
	 *	A collection of tags associated with an Entitiy
	 *
	 *	@changelog
	 *	2013.09.02
	 *		Created
	 */

	PackageManager::Import('Core.Common.AccessorClass');
	PackageManager::Import('Core.Common.Database');

	class TagCollection extends AccessorClass {
		private $entityid;
		private $tags;

		public function get_Collection(){
			return $this->tags;
		}

		public function __construct($entity, $load = true){
			$this->entityid = (is_numeric($entity) ? $entity : $entity->Id);
			$this->tags = array();

			if($load){
				$sth = Database::Prepare("SELECT Tag FROM vwEntityTags WHERE EntityId = :id");
				$sth->bindValue('id', $this->entityid, PDO::PARAM_INT);
				$sth->setFetchMode(PDO::FETCH_OBJ);
				$sth->execute();

				while(($tag = $sth->fetch()) != null)
					$this->tags[] = $tag->Tag;
			}
		}

		public function Contains($tag){
			return in_array($this->tags, $tag);
		}

		public function Add($tag){
			if(!in_array($tag, $this->tags)){
				$sth = Database::Prepare("INSERT IGNORE INTO tblTags (id_entity, id_tag) VALUES (:id_entity, (SELECT id FROM tblTagTypes WHERE name = :tag))");
				$sth->bindValue('id_entity', $this->entityid, PDO::PARAM_INT);
				$sth->bindValue('tag', $tag, PDO::PARAM_STR);
				
				if($sth->execute())
					$this->tags[] = $tag;
			}
		}

		public function Remove($tag){
			if(!in_array($tag, $this->tags)){
				$sth = Database::Prepare("DELETE FROM tblTags id_entity = :id_entity AND  id_tag = (SELECT id FROM tblTagTypes WHERE name = :tag))");
				$sth->bindValue('id_entity', $this->entityid, PDO::PARAM_INT);
				$sth->bindValue('tag', $tag, PDO::PARAM_STR);
				
				if($sth->execute())
					if(($index = array_search($tag, $this->tags)) !== false)
						unset($this->tags[$index]);
			}

		}
	}

?>
