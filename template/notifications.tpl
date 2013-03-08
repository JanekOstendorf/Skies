{if isset($notifications)}

	{foreach from=$notifications key=type item=messages}

		{$template = $notificationTemplates.$type}
		{foreach from=$messages item=message}
			{include file="notifications/$template"}
		{/foreach}

	{/foreach}

{/if}