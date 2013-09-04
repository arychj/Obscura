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
	 *	Obscura.Core.Entities.TagCollection
	 *	A collection of tags associated with an Entitiy
	 *
	 *	@changelog
	 *	2013.09.02
	 *		Created
	 */

	PackageManager::Import('Core.Common.AccessorClass');

	class TagCollection extends AccessorClass {
		private $entity;
		private $tags;

		public function get_Collection(){
			return $this->tags;
		}

		public function __construct(&$entity, &$tags = null){
			$this->entity = $entity;
			$this->tags = ($tags == null ? array() : $tags);
		}

		public function Contains($tag){
			return in_array($this->tags, $tag);
		}

		public function Add($tag){

		}

		public function Remove($tag){

		}
	}

?>
