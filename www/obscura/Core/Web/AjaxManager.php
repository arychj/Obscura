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

	PackageManager::Import('Core.Common.AccessorClass');
	PackageManager::Import('Core.Entities.*');
	PackageManager::Import('Core.Web.AjaxFormats');

	class AjaxManager extends AccessorClass {
		private $format;
		private $isWritable;
		private $entity;
		private $response;

		protected function get_Format(){
			return $this->format;
		}

		protected function get_Response(){
			return $this->response;
		}

		public function __construct($format, $isWritable = false){
			$this->format = $format;
			$this->isWritable = $isWritable;
		}

		public function Process(){
			$this->response = null;

			if(isset($_GET['entitytype']) && isset($_GET['id'])){
				$type = $_GET['entitytype'];
				$id = $_GET['id'];
				$entity = $this->GetEntity($type, $id);
				
				if($entity != null){
				
					if($this->isWritable && $this->HasUpdate())
						$this->Update($entity);

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

			return true;
		}

		private function HasUpdate(){
			return (sizeof($_POST) > 0);
		}

		private function Update($entity){

		}

		private function GetEntity($type, $id){
			switch($type){
				case EntityTypes::Image: return Image::Retrieve($id);
				case EntityTypes::Photo: return Photo::Retrieve($id);
				case EntityTypes::Album: return Album::Retrieve($id);
				case EntityTypes::Collection: return Collection::Retrieve($id);
				case EntityTypes::Journal: return Journal::Retrieve($id);
				case EntityTypes::Video: return Video::Retrieve($id);
				default: return null;
			}
		}
	}
?>
