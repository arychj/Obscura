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
	 *	Core.Common.DateTimeSet
	 *	<description>
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

	PackageManager::Import('AccessorClass');

	class DateTimeSet extends AccessorClass {
		private $created, $modified;

		protected function get_Created(){
			return $this->created;
		}

		protected function get_Modified(){
			return $this->modified;
		}

		protected function get_Vars(){
			return array(
				'created' => $this->created,
				'modified' => $this->modified
			);
		}

		protected function set_Modified($value){
			$this->modified = $value;
		}

		public function __construct($created, $modified){
			$this->created = $created;
			$this->modified = $modified;
		}
	}

?>
