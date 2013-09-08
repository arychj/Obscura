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
	 *	Obscura.Core.Entities.Url
	 *	An Entity URL
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

	PackageManager::Import('Core.Settings');
	PackageManager::Import('Core.Common.AccessorClass');
	PackageManager::Import('Core.Common.DataTools');

	class Url extends AccessorClass {
		private $entity;
		private $pretty = null, $actual = null;

		protected function get_Pretty(){
			if($this->pretty == null)
				$this->pretty = $this->BuildUrl('Pretty');

			return $this->pretty;
		}

		protected function get_Actual(){
			if($this->actual == null)
				$this->actual = $this->BuildUrl('Actual');

			return $this->actual;
		}

		public function __construct(&$entity){
			$this->entity = $entity;
		}

		public function __toString(){
			return $this->BuildUrl(Settings::GetSetting('UrlFormat'));
		}

		private function BuildUrl($type){
			$key = "UrlFormat{$this->entity->Type}" . ($type == "Actual" ? "" : $type);
			$format = Settings::GetSetting($key);

			if($format != null){
				return rtrim(DataTools::BuildString($format, array(
					'base' => Settings::GetSetting('UrlBase'),
					'id' => $this->entity->Id,
					'title' => str_replace(' ', '-', $this->entity->title)
				)), '-');
			}
			else{
//TODO: Bad Format
			}
		}
	}

?>
