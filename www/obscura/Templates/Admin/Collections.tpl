<div class = "row">
	<div class = "offset1 span3">
		<div class = "row">
			<select class = "span3" id = "ddlCollections" size = "39">{collections-optionList}</select>
		</div>
	</div>
	<div class = "form-horizontal" class = "details">
		<div class = "row">
			<div class = "span8">
				<div class = "row">
					<div class = "offset6 span2">
						<button class = "btn btn-small btn-primary" id = "btnServiceUpdate"><i class = "icon-white icon-play"></i> Update</button>
						<button class = "btn btn-small btn-danger pull-right" id = "btnServiceDelete"><i class = "icon-white icon-trash"></i> Delete</button>
					</div>
				</div>
				<div class = "row">
					<div class = "span5">
						<h5>Details</h5>
						<div class = "control-group">
							<input type = "text" class = "input-xlarge" id = "title" value = "{title}" placeholder = "Title"/>
						</div>
						<div class = "control-group">
							<input type = "text" class = "input-xlarge" id = "tags" value = "{tags}" placeholder = "Tags"/>
						</div>
						<div class = "control-group">
							<textarea class = "span5" id = "description" rows = "5" placeholder = "Description">{description}</textarea>
						</div>
						<div class = "control-group">
							<label class="checkbox inline">
								<input type = "checkbox" id = "active"/> Active
							</label>
						</div>
						<div class = "control-group">
							Hit Count: <span id = "hitcount"></span><br/>
							Url: <a id = "url" href = ""></a>
						</div>
					</div>
					<div class = "span3">
						<div class = "control-group">
							<h5>Thumbnail</h5>
							<a href = "#modalUpload" role = "button" data-toggle = "modal"><div id = "thumbnail" class = "thumbnail" style = "background-image: url({thumbnail-url});"></div></a>
						</div>
					</div>
				</div>
				<div class = "row">
					<div class = "span8">
						<hr/>
					</div>
				</div>
				<div class = "row">
					<div class = "span8">
						<h5>Cover</h5>
						<a href = "#modalUpload" role = "button" data-toggle = "modal"><div id = "cover" class = "thumbnail" style = "background-image: url({cover-url});"></div></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id = "modalUpload" class="modal hide fade" tabindex="-1">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3>Upload</h3>
	</div>
	<div class="modal-body">
		<div class = "row" id = "importSelectFile">
			<div class = "span4">
				<h5>Select image to upload:</h5>
				<input type = "file" id = "filImport" />
				<div class="progress progress-striped"  style = "display: none;">
					<div id = "barImport" class="bar" style="width: 0%;"></div>
				</div>
			</div>
		</div>
	</div>
	<div class = "modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><i class = "icon-remove"></i> Cancel</button>
		<button class="btn btn-success" id = "btnImport"><i class = "icon-arrow-up icon-white"></i> Upload</button>
	</div>
</div>
