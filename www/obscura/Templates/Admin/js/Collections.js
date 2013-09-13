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
 *	Collection management functions
 *
 *	@changelog
 *	2013.09.10
 *		Created
 */

$(document).ready(function(){
	$('#ddlCollections').change(LoadCollection);
	$('#btnManage').click(ManageSets);
	$('#btnUpdate').click(UpdateCollection);
	$('#btnDelete').click(DeleteCollection);
	$('#btnUpload').click(function(){
		element = $(this).data('element');
		UploadImage(function(image){
			$(element).css('background-image', 'url(' + image.url + ')').data('id', image.id);
		});
	});

	$('#thumbnail, #cover').click(function(){
		$('#btnUpload').data('element', this);
	});
});

function ManageSets(){
	var collectionid = $('#ddlCollections').val();
	window.location.href = '/Admin/Sets/';
}

function LoadCollection(){
	var collectionid = $('#ddlCollections').val();

	if(collectionid.length > 0 && collectionid > 0){
		$('#modalProcessing').modal('show');
		$.ajax({
			url: '/admin/Collection/' + collectionid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function (collection){
				LoadEntity(collection);
				$('#cover').css('background-image', 'url(' + collection.cover.url + ')').data('id', collection.cover.id);
				$('#thumbnail').css('background-image', 'url(' + collection.thumbnail.url + ')').data('id', collection.thumbnail.id);
				$('#modalProcessing').modal('hide');
			}
		});
	}
}

function UpdateCollection(){
	var collectionid = $('#ddlCollections').val();

	$('#modalProcessing').modal('show');
	$.ajax({
		url: '/admin/Collection/' + collectionid + '.json',
		type: 'POST',
		data: ({
			'title': $('#title').val(),
			'description': $('#description').val(),
			'cover': $('#cover').data('id'),
			'thumbnail': $('#thumbnail').data('id'),
			'active': ($('#active').is(':checked') ? 1 : 0)
		}),
		dataType: 'json',
		success: function(collection){
			if(collectionid == -1){
				$('#ddlCollections').append($('<option/>').val(collection.id).html(collection.Title));
				$('#ddlCollections').val(collection.id);
			}
			else{
				$('#ddlCollections option:selected').html(collection.title);

			}

			$('#modalProcessing').modal('hide');
		}
	});
}

function DeleteCollection(){
	var collectionid = $('#ddlCollections').val();

	if(collectionid.length > 0 && collectionid > 0){
		$('#modalProcessing').modal('show');
		DeleteEntity('Collection', collectionid, function(){
			$('#ddlCollections option:selected').remove();
			$('#modalProcessing').modal('hide');
		});
	}
}
