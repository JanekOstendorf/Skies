<div id="footer-wrapper">
	<div id="footer" style="line-height: 16px;">
		Skies version {$version} -
		{if $gitHash}Git commit
			<a href="https://github.com/ozzyfant/Skies/commit/{$gitHash}">{$gitHash|substr:0:7}</a>
			-{/if}
		{($benchmarkTime * 1000)|round}ms
	</div>
</div>
