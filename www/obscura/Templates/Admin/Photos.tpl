<div class = "row">
	<div class = "offset1 span3">
		<div class = "row">
			<select class = "span3" id = "ddlCollections">{collections-optionList}</select>
		</div>
		<div class = "row">
			<select class = "span3" id = "ddlAlbums">{albums-optionList}</select>
		</div>
		<div class = "row">
			<select class = "span3" id = "ddlPhotos" size = "25"></select>
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
							<input type = "text" class = "input-xlarge" id = "txtTitle" value = "{title}" placeholder = "Title"/>
						</div>
						<div class = "control-group">
							<input type = "text" class = "input-xlarge" id = "txtTags" value = "{tags}" placeholder = "Tags"/>
						</div>
						<div class = "control-group">
							<textarea class = "span5" id = "txtDescription" rows = "5" placeholder = "Description">{description}</textarea>
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
							<h5>Exif</h5>
							<textarea class = "input-xlarge" id = "txtExif" rows = "5">{exif}</textarea>
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
						<div class = "control-group">
							<img id = "photo" class = "thumbnail" src = "{photo-url}" alt = "{title}" style = "margin: auto;"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
