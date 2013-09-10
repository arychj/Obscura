<html>
	<head>
		<title>{sitetitle} // {pagetitle}</title>
		<script type = "text/javascript" src = "{templatepath}/js/jquery-1.9.1.min.js"></script>
		<script type = "text/javascript" src = "{templatepath}/js/jquery-ui-1.10.2.min.js"></script>
		<script type = "text/javascript" src = "{templatepath}/js/jquery.form.js"></script>
		<link rel="stylesheet" href="{templatepath}/bootstrap/css/bootstrap.min.css"></link>
		<script type="text/javascript" src="{templatepath}/bootstrap/js/bootstrap.min.js"></script>

		<link rel = "stylesheet" href = "{templatepath}/css/obscura.css"/>
		<script type = "text/javascript" src = "{templatepath}/js/obscura.js"></script>
		<script type = "text/javascript" src = "{templatepath}/js/Entity.js"></script>
		<script type = "text/javascript" src = "{templatepath}/js/{pagename}.js"></script>
	</head>
	<body>
		<div id = "header" class = "navbar navbar-fixed-top">
			<div class = "navbar-inner">
				<div class = "container">
					<a class = "brand" href = "Home"><img src = "{templatepath}/images/logo/logo-small.png" alt = ""/> {sitetitle}</a>
					<div id = "nav" class = "nav-collapse">
						<ul class = "nav">
							<li><a href = "/Admin/Collections">Collections</a></li>
							<li><a href = "/Admin/Albums">Albums</a></li>
							<li><a href = "/Admin/Photos">Photos</a></li>
							<li><a href = "/Admin/Images">Images</a></li>
							<li><a href = "/Admin/Settings">Settings</a></li>
							<li><a href = "/Admin/Users">Users</a></li>
						</ul>
						<ul id = "admin" class = "nav pull-right">
							<li class = "dropdown">
								<a href = "#" class = "dropdown-toggle" data-toggle = "dropdown">{username} <b class="caret"></b></a>
								<ul class = "dropdown-menu">
									<li class="divider"></li>
									<li><a href = "#">Logout</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class = "container">
{content}
			<hr/>
			<footer>

			</footer>
		</div>
	</body>
</html>
