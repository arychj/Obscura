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
	 *	Obscura.Core.Web.Security
	 *	<Description>
	 *
	 *	@changelog
	 *	2013.09.08
	 *		Created
	 */

	PackageManager::Import('Core.Common.AccessorClass');
	PackageManager::Import('Core.Common.Database');

	class Security extends AccessorClass {
		var $username = 'user';
		var $password = 'pass'; //yeah yeah, I know... the users are going in the database, just haven't gotten there yet

		protected function get_IsAuthorized(){
			return isset($_SESSION['__obsucra_authorized']);
		}

		private function set_IsAuthorized($value){
			$_SESSION['__obscura_authorized'] = $value;
		}

		protected function get_Username(){
			return $this->username;
		}

		public function __construct(){
			@session_start();
		}

		public function Authorize(){
			if(!$this->IsAuthorized){
				if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_USER'] == $this->username) && ($_SERVER['PHP_AUTH_PW'] == $this->password))
					$this->set_IsAuthorized(true);
				else{
					header('WWW-Authenticate: Basic realm="Obscura"');
					header('HTTP/1.0 401 Unauthorized');
					exit();
				}
			}
		}
	}
?>
