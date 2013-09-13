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
 *	Obscura/Templates/Admin/js/Entity.js
 *	Shared Entity admin functions
 *
 *	@changelog
 *	2013.09.10
 *		Created
 */

function LoadEntity(entity){
	$('#title').val(entity.title);
	$('#description').val(entity.description);
	$('#hitcount').html(entity.hitcount);
	$('#url').html(entity.url).attr('href', entity.url);

	if(entity.active == '1')
		$('#active').attr('checked', 'checked');
	else
		$('#active').removeAttr('checked');

	LoadTags(entity.tags);
}

function DeleteEntity(type, id, callback){
	$.ajax({
		url: '/admin/'+ type + '/' + id + '.json',
		type: 'POST',
		data: ({
			'__action': 'delete'
		}),
		dataType: 'json',
		success: callback
	});
}

function LoadTags(tags){
	$('#tags').val(tags);
}

function ClearEntity(){
	$('#title').val('');
	$('#description').val('');
	$('#tags').val('');
	$('#hitcount').html('');
	$('#url').html('').attr('href', '#');
	$('#active').removeAttr('checked');
}
