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
		private $tags, $exif, $iptc;

		/*** accessors ***/

		protected function get_Title(){
			return $this->GetExifValue('Title', '');
		}

		protected function get_Description(){
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

		protected function get_Exif(){
			return $this->exif;
		}

		protected function get_Iptc(){
			return $this->iptc;
		}

		protected function get_Vars(){
			return $this->tags;
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

		public static function Retrieve($entity){
			return self::FromEntity($entity);
		}

		public static function FromEntity($entity){
			$entityid = (is_numeric($entity) ? $entity : $entity->Id);

			$tags = array();

			$sth = Database::Prepare("SELECT Type, Value FROM vwImageExifData WHERE EntityId = :id_entity");
			$sth->bindValue('id_entity', $entityid, PDO::PARAM_INT);
			if($sth->execute()){
				while(($tag = $sth->fetch()) != null){
					$tags[$tag->Type] = $tag->Value;
				}
			}

			return new ExifCollection($tags);
		}

		private function GetExifValue($name, $default){
			return (array_key_exists($name, $this->tags) ? $this->tags[$name] : $default);
		}

		private static function ReadExif($file){
			$this->exif = exif_read_data($file);
			$this->iptc = self::ReadIptcData($file);
			$tags = array();
			
			if(isset($iptc['Title']))
				$tags['Title'] = $iptc['Title'];
			if(isset($iptc['City']))
				$tags['City'] = $iptc['City'];
			if(isset($iptc['State / Province']))
				$tags['State / Province'] = $iptc['State / Province'];
			if(isset($iptc['Country']))
				$tags['Country'] = $iptc['Country'];

			if(isset($exif['ImageDescription']))
				$tags['Description'] = $exif['ImageDescription'];
			if(isset($exif['FNumber']))
				$tags['Aperture'] = self::ResolveFraction($exif['FNumber'], 1);
			if(isset($exif['ExposureTime']))
				$tags['ShutterSpeed'] = $exif['ExposureTime'];
			if(isset($exif['ISOSpeedRatings']))
				$tags['ISO'] = $exif['ISOSpeedRatings'];
			if(isset($exif['FocalLength']))
				$tags['FocalLength'] = substr($exif['FocalLength'], 0, -2);
			if(isset($exif['COMPUTED']['Width']))
				$tags['Width'] = $exif['COMPUTED']['Width'];
			if(isset($exif['COMPUTED']['Height']))
				$tags['Height'] = $exif['COMPUTED']['Height'];
			if(isset($exif['DateTimeOriginal']))
				$tags['TimeTaken'] = $exif['DateTimeOriginal'];
			if(isset($exif['Make']))
				$tags['CameraMake'] = $exif['Make'];
			if(isset($exif['Model']))
				$tags['CameraModel'] = $exif['Model'];
			if(isset($exif['Artist']))
				$tags['Author'] = $exif['Artist'];
			if(isset($exif['Copyright']))
				$tags['Copyright'] = $exif['Copyright'];
			if(isset($exif['GPSLatitudeRef']) && isset($exif['GPSLatitude']))
				$tags['Latitude'] = ($exif['GPSLatitudeRef'] == 'S' ? '-' : '') . self::ResolveFraction($exif['GPSLatitude'][1], 5);
			if(isset($exif['GPSLongitudeRef']) && isset($exif['GPSLongitude']))
				$tags['Longitude'] = ($exif['GPSLongitudeRef'] == 'W' ? '-' : '') . self::ResolveFraction($exif['GPSLongitude'][1], 5);

			return $tags;
		}

		private static function ReadIptcData($image_path){
			$size = getimagesize($image_path, $info);
			
			$iptc = array();

			if(is_array($info)){
				if(isset($info['APP13'])){
					$data = iptcparse($info['APP13']);
					foreach(array_keys($data) as $id){
						$c = count($data[$id]);
						for ($i = 0; $i < $c; $i++) {
							$value = $data[$id][$i];

							if($id == '2#025'){
								if(!isset($iptc['Keywords']))
									$iptc['Keywords'] = array();

								$iptc['Keywords'][] = $value;
							}
							else{
								$title = self::GetIptcTagTitle($id);
								if($title != null)
									$iptc[$title] = $value;
							}
						}
					}
				}
			}

			return $iptc;
		}

		private static function GetIptcTagTitle($id){
			switch($id){
				case '2#005': return 'Title';
				case '2#090': return 'City';
				case '2#095': return 'State / Province';
				case '2#101': return 'Country';
				case '2#120': return 'Description';
				default: return null;
			}
		}

		private static function ResolveFraction($fraction, $places = null){
			$ators = explode('/', $fraction);

			if(sizeof($ators) == 2){
				$number =  $ators[0] / $ators[1];
				return ($places == null ? $number : number_format($number, $places));
			}
			else
				return $fraction;
		}
	}

?>
