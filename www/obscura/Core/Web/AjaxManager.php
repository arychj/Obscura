<?
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
	 *	Obscura.Core.Web.AjaxManager
	 *	<Description>
	 *
	 *	@changelog
	 *	2013.09.09
	 *		Created
	 */

	PackageManager::Import('Core.Settings');
	PackageManager::Import('Core.Common.AccessorClass');
	PackageManager::Import('Core.Common.DataTools');
	PackageManager::Import('Core.Common.Exceptions.SecurityException');
	PackageManager::Import('Core.Entities.*');
	PackageManager::Import('Core.Web.AjaxFormats');

	class AjaxManager extends AccessorClass {
		private $format;
		private $isWritable, $isAdmin;
		private $entity;
		private $response;

		protected function get_Format(){
			return $this->format;
		}

		protected function get_IsWritable(){
			return $this->isWritable;
		}

		protected function get_IsAdmin(){
			return $this->isAdmin;
		}

		protected function get_Response(){
			return $this->response;
		}

		protected function set_IsWritable($value){
			$this->isWritable = $value;
		}

		protected function set_IsAdmin($value){
			$this->isAdmin = $value;
		}

		public function __construct($format, $isWritable = false, $isAdmin = false){
			$this->format = $format;
			$this->isWritable = $isWritable;
			$this->isAdmin = $isAdmin;
		}

		public function Process(){
			$this->response = null;

			if(isset($_GET['type']) && isset($_GET['id'])){
				$type = $_GET['type'];
				$id = $_GET['id'];

				if($type == 'Setting' && $this->isAdmin){
					if($this->HasUpdate()){
						if($this->GetAction() == 'delete'){
							$this->DeleteSetting($id);
							$this->response = '{"result":"success"}';
						}
						else
							$id = $this->UpdateSetting($id);
					}

					if($this->response == null)
						$this->response = $this->GetSetting($id);
				}
				elseif($type == 'User'){

				}
				else{
					$entity = $this->GetEntity($type, $id);
					if($entity != null){
						if($this->isAdmin || $entity->isActive){
							if($this->HasUpdate()){
								if($this->GetAction() == 'delete'){
									$this->DeleteEntity($entity);
									$this->response = '{"result":"success"}';
								}
								else
									$this->UpdateEntity($entity);
							}

							if($this->response == null){
								switch($this->format){
									case AjaxFormats::Json:
										$this->response = $entity->ToJson();
										break;
									case AjaxFormats::Xml:
										$this->reponse = $entity->ToXml();
										break;
								}
							}
						}
					}
				}
			}

			return true;
		}

		private function HasUpdate(){
			return (sizeof($_POST) > 0);
		}

		private function GetAction(){
			return (isset($_POST['__action']) ? $_POST['__action'] : null);
		}

		private function GetSetting($id){
			if($this->isAdmin)
				return Settings::GetSettingJsonById($id, true);
			else
				throw new SecurityException('User does not have sufficient privileges to view settings');
		}

		private function UpdateEntity($entity){
			if($this->isAdmin && $this->isWritable){
				$title = (isset($_POST['title']) ? $_POST['title'] : null);
				$active = (isset($_POST['active']) ? DataTools::ParseBool($_POST['active']) : null);
				$description = (isset($_POST['description']) ? $_POST['description'] : null);
				$cover = (isset($_POST['cover']) ? Image::Retrieve($_POST['cover']) : null);
				$thumbnail = (isset($_POST['thumbnail']) ? Image::Retrieve($_POST['thumbnail']) : null);
				$photo = (isset($_POST['photo']) ? Image::Retrieve($_POST['photo']) : null);

				switch($entity->Type){
					case EntityTypes::Photo: return $entity->Update($title, $description, $photo, $thumbnail, $active);
					case EntityTypes::Set: return $entity->Update($title, $description, $cover, $thumbnail, $active);
					case EntityTypes::Collection: return $entity->Update($title, $description, $cover, $thumbnail, $active);
				}

				if(isset($_POST['tags'])){
					//TODO: Update tags
				}
			}
			else
				throw new SecurityException('User does not have sufficient privileges to delete Entities');
		}

		private function DeleteEntity($entity){
			if($this->isAdmin && $this->isWritable)
				$entity->Delete();
			else
				throw new SecurityException('User does not have sufficient privileges to delete Entities');
		}

		private function UpdateSetting($id){
			if($this->isAdmin && $this->isWritable)
				return Settings::UpdateSetting($id, $_POST['name'], $_POST['value'], DataTools::ParseBool($_POST['encrypted']));
			else
				throw new SecurityException('User does not have sufficient privileges to update settings');
		}

		private function DeleteSetting($id){
			if($this->isAdmin && $this->isWritable)
				Settings::DeleteSetting($id);
			else
				throw new SecurityException('User does not have sufficient privileges to delete settings');
		}

		private function GetEntity($type, $id){
			switch($type){
				case EntityTypes::Image: return Image::Retrieve($id);
				case EntityTypes::Photo: return Photo::Retrieve($id);
				case EntityTypes::Set: return Set::Retrieve($id);
				case EntityTypes::Collection: return Collection::Retrieve($id);
				case EntityTypes::Journal: return Journal::Retrieve($id);
				case EntityTypes::Video: return Video::Retrieve($id);
				default: return null;
			}
		}
	}
?>
