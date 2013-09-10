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
				$sth = Database::Prepare('SELECT id AS Id, name AS Name, value AS Value, tfEncrypted as IsEncrypted FROM tblSettings WHERE name = :name');
				$sth->bindValue('name', $name, PDO::PARAM_STR);

				if($sth->execute())
					self::$cache[$name] = $sth->fetch();
				else
					throw new DatabaseException("Unable to fetch setting with name '$name' from database");
			}

			return self::$cache[$name];
		}

		public static function GetSettingValue($name){
			$setting = self::GetSetting($name);
			return $setting->Value;
		}

		public static function All(){
			$sth = Database::Prepare('SELECT id AS Id, name AS Name, value AS Value, tfEncrypted AS IsEncrypted FROM tblSettings ORDER BY name ASC');
			$sth->execute();

			$settings = array();
			while(($setting = $sth->fetch()) != null)
				$settings[] = $setting;

			return $settings;
		}

		public static function GetSettingById($id){
			$sth = Database::Prepare('SELECT id AS Id, name AS Name, value AS Value, tfEncrypted as IsEncrypted FROM tblSettings WHERE id = :id');
			$sth->bindValue('id', $id, PDO::PARAM_INT);

			if(!$sth->execute())
				throw new DatabaseException("Unable to fetch setting with name '$name' from database");

			return $sth->fetch();
		}

		public static function GetSettingJsonById($id){
			$setting = self::GetSettingById($id);
			return str_replace('\/', '/', json_encode($setting));
		}

		public static function UpdateSetting($id, $name, $value, $isEncrypted){
			if($id == -1){
				$sth = Database::Prepare("INSERT INTO tblSettings (name, value, tfEncrypted) VALUES (:name, :value, :isEncrypted)");
				$sth->bindValue('name', $name, PDO::PARAM_STR);
				$sth->bindValue('value', $value, PDO::PARAM_STR);
				$sth->bindValue('isEncrypted', $isEncrypted, PDO::PARAM_BOOL);
				$sth->execute();

				return Database::LastInsertId();
			}
			else{
				$sth = Database::Prepare("UPDATE tblSettings SET name = :name, value = :value, tfEncrypted = :isEncrypted WHERE id = :id");
				$sth->bindValue('id', $id, PDO::PARAM_INT);
				$sth->bindValue('name', $name, PDO::PARAM_STR);
				$sth->bindValue('value', $value, PDO::PARAM_STR);
				$sth->bindValue('isEncrypted', $isEncrypted, PDO::PARAM_BOOL);
				$sth->execute();

				return $id;
			}
		}

		public static function DeleteSetting($id){
			$sth = Database::Prepare("DELETE FROM tblSettings WHERE id = :id");
			$sth->bindValue('id', $id, PDO::PARAM_INT);
			$sth->execute();
		}
	}

?>
