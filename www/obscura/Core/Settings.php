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
	 *	Core.Settings
	 *	Obscura settings
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

	PackageManager::Import('Core.Common.Database');
	PackageManager::Import('Core.Common.Exceptions.DatabaseException');

	class Settings {
		private static $cache = array();
			
		public static function GetSetting($name){
			if(!array_key_exists($name, self::$cache)){
				$sth = Database::Prepare('SELECT value FROM tblSettings WHERE name = :name');
				$sth->bindValue('name', $name, PDO::PARAM_STR);

				if($sth->execute()){
					self::$cache[$name] = $sth->fetch()->value;
				}
				else
					throw new DatabaseException("Unable to fetch setting with name '$name' from database");
			}

			return self::$cache[$name];
		}

		public static function GetSettingById($id){

		}

		public static function UpdateSetting($id, $name, $value, $isEncrypted){

		}
	}

?>
