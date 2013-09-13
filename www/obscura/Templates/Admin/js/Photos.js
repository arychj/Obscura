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
	$('#btnDelete').click(DeletePhoto);
	$('#btnUpload').click(function(){
		element = $(this).data('element');
		if($(element).attr('id') == 'btnBatchUpload'){
			UploadPhotos($('#ddlSets').val(), function(photos){
				var lastid = 0;
				$(photos).each(function(){
					$('#ddlPhotos').append($('<option/>').attr('value', this.id).html(this.title));
					lastid = this.id;
				});

				$('#ddlPhotos').val(lastid).change();
				$('#modalUpload').modal('hide');
			});
		}
		else if($(element).attr('id') == 'thumbnail'){
			UploadImage(function(image){
				$(element).css('background-image', 'url(' + image.url + ')').data('id', image.id);
				$('#modalUpload').modal('hide');
			});
		}
		else if($(element).attr('id') == 'photo'){
			UploadPhoto(function(photo){
				$(photo).attr('src', photo.url).data('id', photo.id);
				$('#modalUpload').modal('hide');
			});
		}
	});

	$('#thumbnail, #photo').click(function(){
		$('#modalUpload form input').removeAttr('multiple');
		$('#btnUpload').data('element', this);
	});

	$('#btnBatchUpload').click(function(){
		$('#modalUpload form input').attr('multiple', 'multiple');
		$('#btnUpload').data('element', this);
		$('#modalUpload').modal('show');
	});
});

function LoadSets(){
	var collectionid = $('#ddlCollections').val();

	if(collectionid.length > 0 && collectionid > 0){
		$('#modalProcessing').modal('show');
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
				$('#modalProcessing').modal('hide');
			}
		});
	}
}

function LoadPhotos(){
	var setid = $('#ddlSets').val();

	ClearForm();

	if(setid.length > 0 && setid > 0){
		$('#modalProcessing').modal('show');
		$.ajax({
			url: '/admin/Set/' + setid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(set){
				$(set.photos).each(function(){
					$('#ddlPhotos').append($('<option/>').attr('value', this.id).html(this.title));
				});
				$('#modalProcessing').modal('hide');
			}
		});
	}
}

function LoadPhoto(){
	var photoid = $('#ddlPhotos').val();

	if(photoid.length > 0 && photoid > 0){
		$('#modalProcessing').modal('show');
		$.ajax({
			url: '/admin/Photo/' + photoid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(photo){
				LoadEntity(photo);
				$('#photo').attr('src', photo.photo.url);
				$('#thumbnail').css('background-image', 'url(' + photo.thumbnail.url + ')');
				$('#modalProcessing').modal('hide');
			}
		});
	}
}

function UpdatePhoto(){
	var photoid = $('#ddlPhotos').val();

	$('#modalProcessing').modal('show');

	$.ajax({
		url: '/admin/Photo/' + photoid + '.json',
		type: 'POST',
		data: ({
			'title': $('#title').val(),
			'description': $('#description').val(),
			'photo': $('#photo').data('id'),
			'thumbnail': $('#thumbnail').data('id'),
			'active': ($('#active').is(':checked') ? 1 : 0)
		}),
		dataType: 'json',
		success: function(photo){
			if(photoid == -1){
				$('#ddlPhotos').append($('<option/>').val(photo.id).html(photo.Title));
				$('#ddlPhotos').val(photo.id);
			}
			else{
				$('#ddlPhotos option:selected').html(photo.title);

			}

			$('#modalProcessing').modal('hide');
		}
	});
}

function DeletePhoto(){
	var photoid = $('#ddlPhotos').val();

	if(photoid.length > 0 && photoid > 0){
		$('#modalProcessing').modal('show');
		DeleteEntity('Photo', photoid, function(){
			$('#ddlPhotos option:selected').remove();
			$('#modalProcessing').modal('hide');
		});
	}
}

function ClearForm(){
	ClearEntity();
	$('#ddlPhotos').empty();
}
