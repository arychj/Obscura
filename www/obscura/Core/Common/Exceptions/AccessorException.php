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
	 *	Obscura.Core.Common.Exceptions.EntityException
	 *	Base class for exceptions thrown by Accessors
	 *
	 *	@changelog
	 *	2013.08.30
	 *		Created
	 */

	PackageManager::Import('Core.Common.Exceptions.ObscuraException');

	class AccessorException extends ObscuraException{

	}

?>
