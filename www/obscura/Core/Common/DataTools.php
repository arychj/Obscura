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
	 *	Core.Common.DataTools
	 *	Shared data manipulation tools
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

	class DataTools {
		
		public static function BuildString($template, $vars){
			foreach($vars as $var => $val)
				$template = str_replace('{' . $var . '}', $val, $template);

			return $template;
		}

		public static function ParseBool($s){
			if($s == true || $s == 1 || $s == "1" || strtolower($s) == "true")
				return true;
			else
				return false;
		}
	}

?>
