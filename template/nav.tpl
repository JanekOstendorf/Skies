<nav id="hornav">
	<ul>
		{foreach $nav.entries as $entry}
			{$classes = null}
			{if isset($entry.first) && $entry.first}
				{$classes = "`$classes`first "}
			{/if}
			{if isset($entry.last) && $entry.last}
				{$classes = "`$classes`last "}
			{/if}
			{if isset($entry.active) && $entry.active}
				{$classes = "`$classes`active"}
			{/if}
			<li{if $classes} class="{$classes|trim}"{/if}>
				<a href="{$entry.link}">{$entry.title}</a>
			</li>
		{/foreach}
	</ul>

	<br class="clear" />

</nav>
