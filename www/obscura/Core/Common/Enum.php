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
	 *	Obscura.Core.Common.Enum
	 *	<descrption>
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

	abstract class Enum {
		static function __get($accessor) {
			$method = "get_$accessor";
			if(method_exists($this, $method))
				return $this->$method();
			else
				throw new AccessorException("undefined get accessor $accessor");
		}

		staic function __set($accessor, $value) {
			$method = "set_$accessor";
			if(method_exists($this, $method))
				return $this->$method($value);
			else
				user_error("undefined set accessor $accessor");
		}
	}

?>
