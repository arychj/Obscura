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
 *	Obscura/Templates/Admin/js/obscura.js
 *	Global admin functions
 *
 *	@changelog
 *	2013.09.10
 *		Created
 */

$(document).ready(function(){
	HighlightActiveNavLink();
});

function HighlightActiveNavLink() {
	var $currentPage = $('#nav a').filter(function () {
		var regex = /^\/?(.*\/?[\d\w_-]+(?:\.[\d\w_-]{2,4})?)(\?.*)?$/i;
		var path = (document.location.pathname == '/' && false ? 'index.php' : document.location.pathname);

		var current = regex.exec(path);
		var proposed = regex.exec($(this).attr('href'));

		if (current != null && current.length > 0 && proposed != null && proposed.length > 0)
			return (current[1].toLowerCase() == proposed[1].toLowerCase());
		else
			return false;
	});

	if ($currentPage.length == 1) {
		$currentPage.parent('li').addClass('active');
		$currentPage.parents('li.dropdown').addClass('active');
	}
}
