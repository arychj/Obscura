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
	 *	Obscura.Core.Common.Database
	 *	Controls connections to the backend database
	 *
	 *	@changelog
	 *	2013.08.30
	 *		Created
	 */

	PackageManager::Import('Config');

	class Database{
		private static $db = null;

		public static function Prepare($stmt){
			self::Init();

			$sth = self::$db->prepare($stmt);
			$sth->setFetchMode(PDO::FETCH_OBJ);

			return $sth;
		}

		public static function LastInsertId(){
			self::Init();

			return self::$db->lastInsertId();
		}

		private static function Init(){
			if(self::$db == null)
				self::$db = new PDO("mysql:host=" . Config::DatabaseHost . ";dbname=" . Config::DatabaseSchema, Config::DatabaseUsername, Config::DatabasePassword);  
		}

		public static function Connection(){
			self::Init();	
			return self::$db;
		}

		public function Call($proc, $params){

		}

		public function Query($query){

		}
	}

?>
