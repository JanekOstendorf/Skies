<!DOCTYPE html>
<html>
	{include file="head.tpl"}
	<body>

		<!-- Start wrapper -->
		<div id="wrapper">

			<!-- Start navigation -->
			{include file="nav.tpl"}
			<!-- End navigation -->


			<!-- Start logo -->
			<div id="logo">
				<img src="/{$subdir}images/logo.png" />
			</div>
			<!-- End logo -->


			<!-- Start conent -->
			<div id="content">

				{$includePage = $page.templateName}
				{include file="pages/$includePage"}

			</div>
			<!-- End content -->


			<!-- Start footer -->
			<div id="footer-wrapper">
				<div id="footer" style="line-height: 16px;">
					This is GNU GPL, do with it what you want. Hosted by SkyIrc.
					- <a href="https://github.com/ozzyfant/Skies"><img style="width: 16px; vertical-align: middle;" src="/{$subdir}images/github.png" /></a>
					- {($benchmarkTime * 1000)|round}ms
				</div>
			</div>
			<!-- End footer -->

		</div>
		<!-- End wrapper -->
	</body>
</html>
