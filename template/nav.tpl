{function nav level=0}
	<ul>
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
					{nav level=level+1 entries=$entry.subEntries}
				{/if}
			</li>
		{/foreach}
	</ul>
{/function}

<nav id="hornav">

	{nav entries=$nav.entries}
	<br class="clear" />

</nav>


