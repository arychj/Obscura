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
	 *	Obscura.Core.Entities.EntityCollection
	 *	<Description>
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

	PackageManager::Import('Core.Common.AccessorClass');
	PackageManager::Import('Core.Common.Database');
	PackageManager::Import('Core.Entities.Entity');
	PackageManager::Import('Core.Entities.EntityTypes');

	class EntityCollection extends AccessorClass {
		private $entityid;
		private $members;
		private $memberType;

		/*** accessors ***/

		protected function get_Members(){
			return $this->members;
		}

		protected function get_Vars(){
			$vars = array();

			foreach($this->members as $member)
				$vars[] = $member->ShortVars;

			return $vars;
		}

		/*** end accessors ***/

		protected function __construct($entity, $memberType){
			$this->memberType = $memberType;

			if($entity == null){
				$this->entityid = null;

				$sth = Database::Prepare("SELECT id, id_type, title, description, hits, dtCreated, dtModified, tfActive FROM tblEntities WHERE id_type = (SELECT id FROM tblEntityTypes WHERE name = :type)");
				$sth->bindParam('type', $memberType, PDO::PARAM_STR);
				$sth->execute();

				$this->members = array();
				while(($member = $sth->fetch()) != null)
					$this->members[$member->id] = Entity::Build(
						$member->id,
						$member->id_type,
						$memberType,
						$member->title,
						$member->description,
						$member->hits,
						$member->dtCreated,
						$member->dtModified,
						$member->tfActive
					);
			}
			else{
				$this->entityid = (is_numeric($entity) ? $entity : $entity->Id);

				$sth = Database::Prepare("SELECT id_member FROM tblMemberMap WHERE id_entity = :id_entity");
				$sth->bindValue('id_entity', $this->entityid, PDO::PARAM_INT);
				$sth->execute();

				$this->members = array();
				while(($member = $sth->fetch()) != null)
					$this->members[$member->id_member] = $this->GetMember($member->id_member);
			}
		}

		public function ContainsId($id){
			return array_key_exists($id, $this->members);
		}

		public function Contains($entity){
			return $this->ContainsId($entity->Id());
		}

		public function Add($member){
			if($this->entityid != null){
				$memberid = (is_numeric($member) ? $member : $member->Id);

				$sth = Database::Prepare("INSERT IGNORE INTO tblMemberMap WHERE id_entity = :id_entity AND id_member = :id_member");
				$sth->bindValue('id_entity', $this->entityid, PDO::PARAM_INT);
				$sth->bindValue('id_member', $memberid, PDO::PARAM_INT);
				$sth->execute();
			}
			else
				throw new EntityCollectionException("A member cannot be added to a global collection.");
		}

		public function Remove($member){
			if($this->entityid != null){
				$memberid = (is_numeric($member) ? $member : $member->Id);

				$sth = Database::Prepare("DELETE FROM tblMemberMap WHERE id_entity = :id_entity AND id_member = :id_member");
				$sth->bindValue('id_entity', $this->entityid, PDO::PARAM_INT);
				$sth->bindValue('id_member', $memberid, PDO::PARAM_INT);
				$sth->execute();

				unset($this->members[$member->Id]);
			}
			else
				throw new EntityCollectionException("A member cannot be removed from a global collection.");
		}

		public function ToXml($start = 0, $size = 0){

		}

		private function GetMember($id){
			switch($this->memberType){
				case EntityTypes::Image: return Image::Retrieve($id);
				case EntityTypes::Photo: return Photo::Retrieve($id);
				case EntityTypes::Album: return Album::Retrieve($id);
				case EntityTypes::Collection: return Journal::Retrieve($id);
				case EntityTypes::Video: return Video::Retrieve($id);
				case EntityTypes::Journal: return Journal::Retrieve($id);
			}
		}

		public static function Retrieve($id, $memberType){
			return new EntityCollection($id, $memberType);
		}

		public static function All($memberType){
			return new EntityCollection(null, $memberType);
		}
	}

?>
