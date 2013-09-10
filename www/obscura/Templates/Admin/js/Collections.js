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
 *	Obscura/Templates/Admin/js/Collections.js
 *	Collection admin functions
 *
 *	@changelog
 *	2013.09.10
 *		Created
 */

$(document).ready(function(){
	$('#ddlCollections').change(LoadCollection);
	$('#btnUpdate').click(UpdateCollection);
	$('#btnDelete').click(DeleteCollection);
});

function LoadCollection(){
	var collectionid = $('#ddlCollections').val();

	if(collectionid.length > 0 && collectionid > 0){
		$.ajax({
			url: '/admin/Collection/' + collectionid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function (collection){
				LoadEntity(collection);
				$('#cover').css('background-image', 'url(' + collection.cover.url + ')');
				$('#thumbnail').css('background-image', 'url(' + collection.thumbnail.url + ')');
			}
		});
	}
}

function UpdateCollection(){
	var collectionid = $('#ddlCollections').val();

	$.ajax({
		url: '/admin/Collection/' + collectionid + '.json',
		type: 'POST',
		data: ({
			'title': $('#title').val(),
			'description': $('#description').val()
		}),
		dataType: 'json',
		success: function(collection){
			if(collectionid == -1){
				$('#ddlCollections').append($('<option/>').val(collection.id).html(collection.Title));
				$('#ddlCollections').val(collection.id);
			}
			else{
				$('#title').val(collection.Title);
				$('#description').val(collection.Description);

			}
		}
	});
}

function DeleteCollection(){
	var collectionid = $('#ddlCollections').val();

	if(collectionid.length > 0 && collectionid > 0){
		DeleteEntity('Collection', collectionid, function(){
			
		});
	}
}
