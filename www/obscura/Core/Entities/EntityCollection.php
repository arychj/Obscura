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

	class EntityCollection extends AccessorClass {
		private $entity;
		private $members;
		private $memberType;

		/*** accessors ***/

		protected function get_Members(){
			return $members;
		}

		/*** end accessors ***/

		protected function __construct($entity, $memberType){
			$this->entity = $entity;
			$this->memberType = $memberType;

			$sth = Database::Prepare("SELECT id_member FROM tblMemberMap WHERE id_entity = :id_entity");
			$sth->bindValue('id_entity', $entity->Id, PDO::PARAM_INT);
			$sth->execute();

			$this->members = array();
			while(($member = $sth->fetch()) != null)
				$this->members[$member->id_member] = $this->GetMember($memberType, $member->id_member);
		}

		public function ContainsId($id){
			return array_key_exists($id, $this->members);
		}

		public function Contains($entity){
			return $this->ContainsId($entity->Id());
		}

		public function Add($entity){
			
		}

		public function Remove($member){
			$memberid = (is_int($member) ? $member : $member->Id);

			$sth = Database::Prepare("DELETE FROM tblMemberMap WHERE id_entity = :id_entity AND id_member = :id_member");
			$sth->bindValue('id_entity', $this->entity->Id, PDO::PARAM_INT);
			$sth->bindValue('id_member', $memberid, PDO::PARAM_INT);
			$sth->execute();

			unset($this->members[$member->Id]);
		}

		public function ToXml($start = 0, $size = 0){

		}

		private static GetMember($type, $id){
			switch($type){
				case EntityType::Image: return Image::Retrieve($id);
				case EntityType::Photo: return Photo::Retrieve($id);
				case EntityType::Album: return Album::Retrieve($id);
				case EntityType::Collection: return Journal::Retrieve($id);
				case EntityType::Video: return Video::Retrieve($id);
				case EntityType::Journal: return Journal::Retrieve($id);
			}
		}

		public static function Retrieve($entity){
			$entityid = (is_int($entity) ? $entity : $entity->Id);


		}
	}

?>
