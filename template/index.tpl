<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>

		<title>{$config.meta.title} &bull; {$page->getTitle()}</title>

		{foreach $style->getCssFiles() as $file}
		<link rel="stylesheet" type="text/css" href="/{$style->getStyleDirUrl()}{$file}" />
		{/foreach}

		<script type="text/javascript" src="/{$subdir}js/jQuery.js"></script>

		{foreach $style->getJsFiles() as $file}
		<script type="text/javascript" src="/{$style->getStyleDirUrl()}{$file}"></script>
		{/foreach}

	</head>
	<body>

		<!-- Start wrapper -->
		<div id="wrapper">

			<!-- Start navigation -->
			{*<?php (new \skies\system\navigation\Navigation(1))->printNav(); ?> *}
			<!-- End navigation -->


			<!-- Start logo -->
			<div id="logo">
				<img src="/{$subdir}images/logo.png" />
			</div>
			<!-- End logo -->


			<!-- Start conent -->
			<div id="content">


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
