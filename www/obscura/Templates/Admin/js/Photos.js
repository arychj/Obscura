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
	$('#ddlCollections').change(LoadSets);
	$('#ddlSets').change(LoadPhotos);	
	$('#ddlPhotos').change(LoadPhoto);	
});

function LoadSets(){
	var collectionid = $('#ddlCollections').val();

	if(collectionid.length > 0 && collectionid > 0){
		$('#ddlSets').empty();

		$.ajax({
			url: '/admin/Collection/' + collectionid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(collection){
				$('#ddlSets').append($('<option/>').attr('value', ''));
				$(collection.sets).each(function(){
					$('#ddlSets').append($('<option/>').attr('value', this.id).html(this.title));
				});
			}
		});
	}
}

function LoadPhotos(){
	var setid = $('#ddlSets').val();

	ClearForm();

	if(setid.length > 0 && setid > 0){
		$.ajax({
			url: '/admin/Set/' + setid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(set){
				$(set.photos).each(function(){
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
