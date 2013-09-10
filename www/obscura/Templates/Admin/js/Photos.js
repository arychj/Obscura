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
 *	Obscura/Templates/Admin/js/Photos.js
 *	Photo admin functions
 *
 *	@changelog
 *	2013.09.10
 *		Created
 */

$(document).ready(function(){
	$('#ddlAlbums').change(LoadPhotos);	
	$('#ddlPhotos').change(LoadPhoto);	
});

function LoadPhotos(){
	var albumid = $('#ddlAlbums').val();

	ClearForm();

	if(albumid.length > 0 && albumid > 0){
		$.ajax({
			url: '/admin/Album/' + albumid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(album){
				$(album.photos).each(function(){
					$('#ddlPhotos').append($('<option/>').attr('value', this.id).html(this.title));
				});
			}
		});
	}
}

function LoadPhoto(){
	var photoid = $('#ddlPhotos').val();

	if(photoid.length > 0 && photoid > 0){
		$.ajax({
			url: '/admin/Photo/' + photoid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(photo){
				LoadEntity(photo);
				$('#photo').attr('src', photo.photo.url);
			}
		});
	}
}

function ClearForm(){
	ClearEntity();
	$('#ddlPhotos').empty();
}
