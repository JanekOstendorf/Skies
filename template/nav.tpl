{function nav level=0}
{if $level == 0}
<ul class="toplevel">
	{else}
	<ul>
		{/if}
		{foreach $entries as $entry}
			{$classes = null}
			{if $entry.entry.isFirst}
				{$classes = "`$classes`first "}
			{/if}
			{if $entry.entry.isLast}
				{$classes = "`$classes`last "}
			{/if}
			{if $entry.subEntries}
				{$classes = "`$classes`dropdown "}
			{/if}
			{if $entry.entry.isActive}
				{$classes = "`$classes`active"}
			{/if}
			<li{if $classes} class="{$classes|trim}"{/if}>
				<a href="{$entry.entry.link}">{$entry.entry.title}</a>
				{if $entry.subEntries}
					<div class="submenu">
						{nav level=level+1 entries=$entry.subEntries}
					</div>
				{/if}
			</li>
		{/foreach}
	</ul>
	{/function}

	<nav id="hornav">

		{nav entries=$nav.entries}
		<br class="clear" />

	</nav>


