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
	 *	Obscura.Core.Entities.ImageSizes
	 *	<Description>
	 *
	 *	@changelog
	 *	2013.09.30
	 *		Created
	 */

	class ImageSizes {
		const Original = 'Original';
		const Large = 'Large';
		const Medium = 'Medium';
		const Small = 'Small';
		const Thumbnail = 'Thumbnail';
		const Product = 'Product';

		public static function Parse($size){
			$symbol = strtolower($size[0]);
			switch($symbol){
				case 'o': return self::Original;
				case 'l': return self::Large;
				case 'm': return self::Medium;
				case 's': return self::Small;
				case 't': return self::Thumbnail;
				case 'p': return self::Product;
				default: return null;
			}
		}
	}

?>
