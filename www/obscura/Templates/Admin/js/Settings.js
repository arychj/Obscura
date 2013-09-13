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
 *	Obscura/Templates/Admin/js/Settings.js
 *	Setting admin functions
 *
 *	@changelog
 *	2013.09.10
 *		Created
 */

$(document).ready(function(){
	$('#ddlSettings').change(LoadSetting);
	$('#btnUpdate').click(UpdateSetting);
	$('#btnDelete').click(DeleteSetting);
});

function LoadSetting(){
	var settingid = $('#ddlSettings').val();

	if(settingid.length > 0 && settingid > 0){
		$.ajax({
			url: '/admin/Setting/' + settingid + '.json',
			type: 'GET',
			dataType: 'json',
			success: function(setting){
				$('#name').val(setting.Name);
				$('#value').val(setting.Value);
				$('#description').val(setting.Description);

				if(setting.IsEncrypted == '1')
					$('#encrypted').attr('checked', 'checked');
				else
					$('#encrypted').removeAttr('checked');
			}
		});
	}
	else{
		$('#name').val('');
		$('#value').val('');
		$('#encrypted').removeProp('checked');
	}
}

function UpdateSetting(){
	var settingid = $('#ddlSettings').val();

	$.ajax({
		url: '/admin/Setting/' + settingid + '.json',
		type: 'POST',
		data: ({
			'name': $('#name').val(),
			'value': $('#value').val(),
			'description': $('#description').val(),
			'encrypted': ($('#encrypted').is(':checked') ? 1 : 0)
		}),
		dataType: 'json',
		success: function(setting){
			if(settingid == -1){
				$('#ddlSettings').append($('<option/>').val(setting.id).html(setting.Name));
				$('#ddlSettings').val(setting.id);
			}
			else{
				$('#name').val(setting.Name);
				$('#value').val(setting.Value);

				if(setting.IsEncrypted)
					$('#encrypted').prop('checked');
				else
					$('#encrypted').removeProp('checked');
			}
		}
	});
}

function DeleteSetting(){
	var settingid = $('#ddlSettings').val();

	$.ajax({
		url: '/admin/Setting/' + settingid + '.json',
		type: 'POST',
		data: ({
			'__action': 'delete'
		}),
		dataType: 'json',
		success: function(setting){
			$('#ddlSettings option:selected').remove();
			$('#name').val('');
			$('#value').val('');
			$('#encrypted').removeProp('checked');
		}
	});
}
