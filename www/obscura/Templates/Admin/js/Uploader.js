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

$(document).ready(function(){
	$('#modalUpload').on('show', function(){
		$(this).find('input').val('').show();
		$(this).find('#progressTotal, #progressFile').hide();
	});
});

function UploadImage(callback){
	Upload('Image', null, function(images){
		callback(images[0]);
	});
}

function UploadImages(photo, callback){
	Upload('Images', 'Photo=' + photo, callback);
}

function UploadPhoto(callback){
	Upload('Photo', null, function(photos){
		callback(photos[0]);
	});
}

function UploadPhotos(set, callback){
	Upload('Photos', 'Set=' + set, callback);
}

function Upload(type, parent,  callback){
	var form = $('#imagesForm');
	var multiple = ($(form).find('input[multiple]').length > 0);
	var count = $(form).find('input').get(0).files.length;

	$(form).find('input').hide();
	$(form).find('.bar').css('width', '0%');

	$(form).find('#progressTotal').show();
	if(multiple)
		$(form).find('#progressFile').show();

	$(form).ajaxSubmit({
		url: 'Uploader/?type=' + type + (parent == null ? '' : '&' + parent),
		dataType: 'json',
		uploadProgress: function(event, position, total, percent){
			$(form).find('#progressTotal .bar').css('width', percent + '%');
			$(form).find('#progressFile .bar').css('width', ((count * percent) % 101) + '%');
		},
		success: function(response){
			callback(response);
			$('#modalUpload').modal('hide');
		}
	});
}
