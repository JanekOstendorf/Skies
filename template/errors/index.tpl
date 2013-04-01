<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<title>{$config.meta.title} &bull; {$error.title}</title>
		<link rel="shortcut icon" type="{$config.faviconMime}" href="/{$subdir}{$config.favicon}">

		<link rel="stylesheet" type="text/css" href="/{$style.dir}{$style.errorCss}" />

	</head>
	<body>
		{include file="errors/{$error.templateName}"}
	</body>
</html>
