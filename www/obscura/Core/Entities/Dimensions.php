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
	 *	Obscura.Core.Entities.Dimensions
	 *	The dimensions of an Image
	 *
	 *	@changelog
	 *	2013.09.02
	 *		Created
	 */

	PackageManager::Import('Core.Common.AccessorClass');

	class Dimensions extends AccessorClass {
		private $width, $height;

		protected function get_Width(){
			return $this->width;
		}

		protected function get_Height(){
			return $this->height;
		}

		protected function get_Vars(){
			return array(
				'width'		=> $this->Width,
				'height'	=> $this->Height
			);
		}

		public function __construct($width, $height){
			$this->width = $width;
			$this->height = $height;
		}
	}

?>
