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
 *	Obscura/Templates/Admin/js/Albums.js
 *	Album admin functions
 *
 *	@changelog
 *	2013.09.10
 *		Created
 */

$(document).ready(function(){
	$('#ddlCollections').change(LoadAlbums);	
	$('#ddlAlbums').change(LoadAlbum);	
});

function LoadAlbums(){
	var collectionid = $('#ddlCollections').val();

	ClearForm();

	if(collectionid.length > 0 && collectionid > 0){
		$.ajax({
			url: '/admin/Collection/' + collectionid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(collection){
				$(collection.albums).each(function(){
					$('#ddlAlbums').append($('<option/>').attr('value', this.id).html(this.title));
				});
			}
		});
	}
}

function LoadAlbum(){
	var albumid = $('#ddlAlbums').val();

	if(albumid.length > 0 && albumid > 0){
		$.ajax({
			url: '/admin/Album/' + albumid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(album){
				LoadEntity(album);
				$('#cover').css('background-image', 'url(' + album.cover.url + ')');
				$('#thumbnail').css('background-image', 'url(' + album.thumbnail.url + ')');
			}
		});
	}
}

function ClearForm(){
	ClearEntity();
	$('#ddlAlbums').empty();
	$('#cover').css('background-image', '');
	$('#thumbnail').css('background-image', '');
}
