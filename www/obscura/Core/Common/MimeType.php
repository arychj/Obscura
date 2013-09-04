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
	 *	Core.Common.MimeType
	 *	<description>
	 *
	 *	@changelog
	 *	2013.09.03
	 *		Created
	 */

	class MimeType {
		public static function ParseExtension($extension) {
            switch ($extension) {
                case "bmp": return "image/bmp";
                case "cr2": return "image/x-canon-cr2";
                case "gif": return "image/gif";
                case "jpeg": return "image/jpeg";
                case "jpg": return "image/jpeg";
                case "png": return "image/png";
                case "tiff": return "image/tiff";
                default: return "text/plain";
            }
        }

        public static function LookupExtension($mimetype) {
            switch ($mimetype) {
                case "image/bmp": return "bmp";
                case "image/x-canon-cr2": return "cr2";
                case "image/gif": return "gif";
                case "image/jpeg": return "jpg";
                case "image/jpg": return "jpg";
                case "image/png": return "png";
                case "image/tiff": return "tiff";
                default: return "txt";
            }
        }
	}

?>