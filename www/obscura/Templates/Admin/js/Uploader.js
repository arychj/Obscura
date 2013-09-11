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
 *	Obscura/Templates/Admin/js/Uploader.js
 *	Uploads images
 *
 *	@changelog
 *	2013.09.10
 *		Created
 */

function UploadImages(type, callback){
	var form = $('#imagesForm');

	$(form).find('input').hide();
	$(form).find('.progress').show();
	$(form).find('.bar').css('width', '0%');

	$(form).ajaxSubmit({
		url: 'Uploader/?type=' + type,
		dataType: 'json',
		uploadProgress: function(event, position, total, percent){
			$(form).find('.bar').css('width', percent + '%');
		},
		success: callback
	});
}
