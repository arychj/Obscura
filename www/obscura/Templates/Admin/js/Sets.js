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
});

function LoadSets(){
	var collectionid = $('#ddlCollections').val();

	ClearForm();

	if(collectionid.length > 0 && collectionid > 0){
		$.ajax({
			url: '/admin/Collection/' + collectionid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(collection){
				$('#ddlSets').append($('<option/>').attr('value', -1).html('-- New --'));
				$(collection.sets).each(function(){
					$('#ddlSets').append($('<option/>').attr('value', this.id).html(this.title));
				});
			}
		});
	}
}

function LoadSet(){
	var setid = $('#ddlSets').val();

	if(setid.length > 0 && setid > 0){
		$.ajax({
			url: '/admin/Set/' + setid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(set){
				LoadEntity(set);
				$('#cover').css('background-image', 'url(' + set.cover.url + ')');
				$('#thumbnail').css('background-image', 'url(' + set.thumbnail.url + ')');
			}
		});
	}
}

function ClearForm(){
	ClearEntity();
	$('#ddlSets').empty();
	$('#cover').css('background-image', '');
	$('#thumbnail').css('background-image', '');
}
