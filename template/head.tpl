<head>

	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

	<title>{$config.meta.title} &bull; {$page.title}</title>

	{foreach $style.cssFiles as $file}
		<link rel="stylesheet" type="text/css" href="/{$style.dir}{$file}" />
	{/foreach}

	<script type="text/javascript" src="/{$subdir}js/jQuery.js"></script>
	{foreach $style.jsFiles as $file}
		<script type="text/javascript" src="/{$style.dir}{$file}"></script>
	{/foreach}

</head>
