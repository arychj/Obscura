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
	 *	Obscura.Core.Entities.ExifCollection
	 *	<Description>
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

	PackageManager::Import('Core.Common.AccessorClass');
	PackageManager::Import('Core.Common.Database');

	class ExifCollection extends AccessorClass{
		private $tags;

		/*** accessors ***/

		protected function get_Title(){
			return $this->GetExifValue('Title', '');
		}

		protected function get_Caption(){
			return $this->GetExifValue('Description', '');
		}

		protected function get_Aperture(){
			return $this->GetExifValue('Aperture', 0);
		}

		protected function get_ShutterSpeed(){
			return $this->GetExifValue('ShutterSpeed', 0);
		}

		protected function get_ISO(){
			return $this->GetExifValue('ISO', 0);
		}

		protected function get_FocalLength(){
			return $this->GetExifValue('FocalLength', 0);
		}

		protected function get_Width(){
			return $this->GetExifValue('Width', 0);
		}

		protected function get_Height(){
			return $this->GetExifValue('Height', 0);
		}

		protected function get_TimeTaken(){
			return $this->GetExifValue('TimeTaken', 0);
		}

		protected function get_CameraMake(){
			return $this->GetExifValue('CamerMake', 'Unknown');
		}

		protected function get_CameraModel(){
			return $this->GetExifValue('CamerModel', 'Unknown');
		}

		protected function get_Author(){
			return $this->GetExifValue('Author', 'Unknown');
		}

		protected function get_Copyright(){
			return $this->GetExifValue('Copyright', '');
		}

		protected function get_Latitude(){
			return $this->GetExifValue('Latitude', 0);
		}

		protected function get_Longitude(){
			return $this->GetExifValue('Longitude', 0);
		}

		/*** end accessors ***/

		protected function __construct($tags){
			$this->tags = $tags;
		}

		public function GetExif($file){
			return new ExifCollection(self::ReadExif($file));
		}

		public function SaveToEntity($entity){
			$sth = Database::Prepare("INSERT INTO tblImageExifData (id_entity, id_type, value) VALUES (:id_entity, (SELECT id FROM tblImageExifTypes WHERE name = :name), :value)");

			foreach($this->tags as $name => $value){
				$sth->execute(array(
					'id_entity' => $entity->Id,
					'name' => $name,
					'value' => $value
				));
			}
		}

		public static function FromArray($tags){
			return new ExifCollection($tags);
		}

		public static function FromEntity($entity){
			$entityid = (is_numeric($entity) ? $entity : $entity->Id);

			$tags = array();

			$sth = Database::Prepare("SELECT name FROM Type, Value FROM vwImageExifData WHERE EntityId = :id_entity");
			$sth->bindValue('id_entity', $entityid, PDO::PARAM_INT);
			if($sth->execute()){
				while(($tag = $sth->fetch()) != null)
					$tags[$etag->Type] = $exif->Value;
			}

			return new ExifCollection($tags);
		}

		private function GetExifValue($name, $default){
			return (array_key_exists($name, $this->tags) ? $this->tags[$name] : $default);
		}

		private static function ReadExif($file){
			$exif = exif_read_data($file);
			$tags = array();
			
			if(isset($exif['Title']))
				$tags['Title'] = $exif['Title'];
			if(isset($exif['Description']))
				$tags['Description'] = $exif['Caption'];
			if(isset($exif['COMPUTED']['ApertureFNumber']))
				$tags['Aperture'] = substr($exif['COMPUTED']['ApertureFNumber'], 2);
			if(isset($exif['ShutterSpeed']))
				$tags['ShutterSpeed'] = $exif['ExposureTime'];
			if(isset($exif['ISO']))
				$tags['ISO'] = $exif['ISOSpeedRatings'];
			if(isset($exif['FocalLength']))
				$tags['FocalLength'] = substr($exif['FocalLength'], 0, -2);
			if(isset($exif['Width']))
				$tags['Width'] = $exif['COMPUTED']['Width'];
			if(isset($exif['Height']))
				$tags['Height'] = $exif['COMPUTED']['Height'];
			if(isset($exif['TimeTaken']))
				$tags['TimeTaken'] = $exif['DateTimeOriginal'];
			if(isset($exif['CameraMake']))
				$tags['CameraMake'] = $exif['Make'];
			if(isset($exif['CamerModel']))
				$tags['CameraModel'] = $exif['Model'];
			if(isset($exif['Author']))
				$tags['Author'] = $exif['Artist'];
			if(isset($exif['Copyright']))
				$tags['Copyright'] = $exif['Copyright'];

			//TODO: Lat/long
			if(isset($exif['Lattitude']))
				$tags['Latitude'] = 'n/a';
			if(isset($exif['Longitude']))
				$tags['Longitude'] = 'n/a';

			return $tags;
		}
	}

?>
