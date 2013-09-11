<div class = "row">
	<div class = "offset1 span3">
		<div class = "row">
			<select class = "span3" id = "ddlCollections">{collections-optionList}</select>
		</div>
		<div class = "row">
			<select class = "span3" id = "ddlSets" size = "35"></select>
		</div>
	</div>
	<div class = "form-horizontal" class = "details">
		<div class = "row">
			<div class = "span8">
				<div class = "row">
					<div class = "span2">
						<button class = "btn btn-small" id = "btnManage"><i class = "icon-align-justify"></i> Manage Photos</button>
					</div>
					<div class = "offset4 span2">
						<button class = "btn btn-small btn-primary" id = "btnUpdate"><i class = "icon-white icon-play"></i> Update</button>
						<button class = "btn btn-small btn-danger pull-right" id = "btnDelete"><i class = "icon-white icon-trash"></i> Delete</button>
					</div>
				</div>
				<div class = "row">
					<div class = "span5">
						<h5>Details</h5>
						<div class = "control-group">
							<input type = "text" class = "input-xlarge" id = "title" value = "{title}" placeholder = "Title"/>
						</div>
						<div class = "control-group">
							<input type = "text" class = "input-xlarge" id = "tags" value ="{tags}" placeholder = "Tags"/>
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
							<div id = "thumbnail" class = "thumbnail" style = "background-image: url({thumbnail-url});"></div>
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
						<div id = "cover" class = "thumbnail" style = "background-image: url({cover-url});"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
