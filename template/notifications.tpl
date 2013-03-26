{if isset($notifications)}

	{foreach from=$notifications key=type item=messages}

		{$cssClass = $notificationTemplates.$type}
		{foreach from=$messages item=message}

			<div class="{$cssClass}">
				<div class="message">
					{$message}
				</div>
				<div class="hideLink" title="{lang node="system.notification.hide"}">
					<a href="javascript:void(0);"><img src="/{$style.dir}images/icons/hide-cross.png" alt="Hide" /></a>
				</div>
				<div class="clear"></div>
			</div>

		{/foreach}

	{/foreach}

{/if}
