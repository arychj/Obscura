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
	 *	Config.php
	 *	Contains configuration variables for the site. Duh?
	 *
	 *	@changelog
	 */

	class Config {
		const Debug = true;

		const DatabaseType = 'MySQL';
		const DatabaseHost = '192.168.1.12';
		const DatabasePort = '3306';
		const DatabaseSchema = 'dev-obscura';
		const DatabaseUsername = 'root';
		const DatabasePassword = '';

		const TemplateDirectory = '/data/www/dev/obscura/www/obscura/Templates';
	}
?>
