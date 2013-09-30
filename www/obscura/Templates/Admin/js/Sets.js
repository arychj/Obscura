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
 *	Obscura/Templates/Admin/js/Sets.js
 *	Set admin functions
 *
 *	@changelog
 *	2013.09.10
 *		Created
 */

$(document).ready(function(){
	$('#ddlCollections').change(LoadSets);	
	$('#ddlSets').change(LoadSet);

	$('#btnUpdate').click(UpdateSet);
	$('#btnDelete').click(DeleteSet);
	$('#btnUpload').click(function(){
		element = $(this).data('element');
		UploadImages(function(image){
			$(element).css('background-image', 'url(' + image.url + ')').data('id', image.id);
			$('#modalUpload').modal('hide');
		});
	});

	$('#thumbnail, #cover').click(function(){
		$('#btnUpload').data('element', this);
	});
});

function LoadSets(){
	var collectionid = $('#ddlCollections').val();

	ClearForm();

	if(collectionid.length > 0 && collectionid > 0){
		$('#modalProcessing').modal('show');

		$.ajax({
			url: '/admin/Collection/' + collectionid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(collection){
				$('#ddlSets').append($('<option/>').attr('value', -1).html('-- New --'));
				$(collection.sets).each(function(){
					$('#ddlSets').append($('<option/>').attr('value', this.id).html(this.title));
				});

				$('#modalProcessing').modal('hide');
			}
		});
	}
}

function LoadSet(){
	var setid = $('#ddlSets').val();

	if(setid.length > 0 && setid > 0){
		$('#modalProcessing').modal('show');

		$.ajax({
			url: '/admin/Set/' + setid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(set){
				LoadEntity(set);
				if(set.cover != null)
					$('#cover').css('background-image', 'url(' + set.cover.url + ')');
				if(set.thumbnail != null)
					$('#thumbnail').css('background-image', 'url(' + set.thumbnail.url + ')');

				$('#modalProcessing').modal('hide');
			}
		});
	}
}

function UpdateSet(){
	var setid = $('#ddlSets').val();

	$('#modalProcessing').modal('show');

	$.ajax({
		url: '/admin/Set/' + setid + '.json',
		type: 'POST',
		data: ({
			'title': $('#title').val(),
			'description': $('#description').val(),
			'cover': $('#cover').data('id'),
			'thumbnail': $('#thumbnail').data('id'),
			'parent': $('#ddlCollections').val(),
			'active': ($('#active').is(':checked') ? 1 : 0)
		}),
		dataType: 'json',
		success: function(set){
			if(setid == -1){
				$('#ddlSets').append($('<option/>').val(set.id).html(set.title));
				$('#ddlSets').val(set.id);
			}
			else{
				$('#ddlSets option:selected').html(set.title);
			}

			$('#modalProcessing').modal('hide');
		}
	});
}

function DeleteSet(){
	var setid = $('#ddlSets').val();

	if(setid.length > 0 && setid > 0){
		$('#modalProcessing').modal('show');
		DeleteEntity('Set', setid, function(){
			$('#ddlSets option:selected').remove();
			$('#modalProcessing').modal('hide');
		});
	}
}

function ClearForm(){
	ClearEntity();
	$('#ddlSets').empty();
	$('#cover').css('background-image', '');
	$('#thumbnail').css('background-image', '');
}
