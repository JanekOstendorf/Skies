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
				<img src="/{$subdir}images/logo.png" alt="logo" />
			</div>
			<!-- End logo -->

			<!-- Start conent -->
			<div id="content">

				{include file="notifications.tpl"}

				{$includeTemplate = $page.templateName}
				{include file="pages/$includeTemplate"}

			</div>
			<!-- End content -->

			<!-- Start footer -->
			{include file="footer.tpl"}
			<!-- End footer -->

		</div>
		<!-- End wrapper -->
	</body>
</html>
