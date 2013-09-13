<div class = "row">
	<div class = "offset1 span3">
		<div class = "row">
			<select class = "span3" id = "ddlCollections">{collections-optionList}</select>
		</div>
		<div class = "row">
			<select class = "span3" id = "ddlSets"></select>
		</div>
		<div class = "row">
			<select class = "span3" id = "ddlPhotos" size = "25"></select>
		</div>
	</div>
	<div class = "form-horizontal" class = "details">
		<div class = "row">
			<div class = "span8">
				<div class = "row">
					<div class = "span3">
						<button class = "btn btn-small" id = "btnBatchUpload"><i class = "icon-arrow-up"></i> Upload Photos</button>
						<button class = "btn btn-small" id = "btnManage"><i class = "icon-align-justify"></i> Manage Images</button>
					</div>
					<div class = "offset3 span2">
						<button class = "btn btn-small btn-primary" id = "btnUpdate"><i class = "icon-white icon-play"></i> Update</button>
						<button class = "btn btn-small btn-danger pull-right" id = "btnDelete"><i class = "icon-white icon-trash"></i> Delete</button>
					</div>
				</div>
				<div class = "row">
					<div class = "span8">
						<hr/>
					</div>
				</div>
				<div class = "row">
					<div class = "span5">
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
							</div>
						</div>
						<div class = "row">
							<div class = "span1">
								<div class = "control-group">
									<label class="checkbox inline">
										<input type = "checkbox" id = "active"/> Active
									</label>
								</div>
							</div>
							<div class = "span4">
								<div class = "control-group">
									<b>Hit Count:</b> <span id = "hitcount"></span><br/>
									<b>Url:</b> <a id = "url" href = ""></a>
								</div>
							</div>
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
						<div class = "control-group">
							<h5>Exif</h5>
							<textarea class = "input-xxlarge" id = "txtExif" rows = "5">{exif}</textarea>
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
							<a href = "#modalUpload" role = "button" data-toggle = "modal"><img id = "photo" class = "thumbnail" src = "{photo-url}" alt = "{title}" style = "margin: auto;"/></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
