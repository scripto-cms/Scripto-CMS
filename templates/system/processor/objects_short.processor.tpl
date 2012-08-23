<div class="object_container">
{if $type.small_preview}
	<div class="object_picture">{if $obj.small_preview}{if $type.full_content}<a href="{$siteurl}{$urls[$obj.id_category]}?id_object={$obj.id_object}" title="{$obj.caption}">{/if}<img src="{$user_thumbnails}{$obj.small_preview}" alt="{$obj.caption}">{if $type.full_content}</a>{/if}{/if}</div>
	<div class="object_description">
{else}
	<div class="object_description_max">
{/if}
		<h3>{if $type.full_content}<a href="{$siteurl}{$urls[$obj.id_category]}?id_object={$obj.id_object}">{/if}{$obj.caption} {foreach key=key item=text from=$obj.values.texts}{if $text.type=="pricerub"}{if $text.value}( {$text.value} руб. ){/if}{elseif $text.type=="priceusd"}{if $text.value}( {$text.value} $ ){/if}{/if}{/foreach}{if $type.full_content}</a>{/if}</h3>
		{if $type.use_code}
			{if $obj.code!=''}<p>Код {$type.fulllink_text}: <b>{$obj.code}</b></p>{/if}
		{/if}
		{if $type.short_content}
			{$obj.small_content}
		{/if}
		<p style="gray">
		{if $obj.values.texts}
			{foreach key=key item=text from=$obj.values.texts}
				{if $text.type=="link"}
					{if $text.value!='' && $text.caption!=''}{$text.caption} : <a href="{$text.value}" target="_blank">{$text.value}</a>; {/if}
				{elseif $text.type=="email"}
					{if $text.value!='' && $text.caption!=''}{$text.caption} : <a href="mailto:{$text.value}">{$text.value}</a>; {/if}
				{elseif $text.type=="pricerub"}
					{if $text.value!='' && $text.caption!=''}{$text.caption} : {$text.value} руб.; {/if}
				{elseif $text.type=="priceusd"}
					{if $text.value!='' && $text.caption!=''}{$text.caption} : {$text.value} $; {/if}					
				{else}
					{if $text.value!='' && $text.caption!=''}{$text.caption} : {$text.value}; {/if}
				{/if}
			{/foreach}
		{/if}
		{if $obj.values.checkbox}
			{foreach key=key item=text from=$obj.values.checkbox}
					{if $text.caption!=''}{$text.caption} : {if $text.value}да{else}нет{/if}; {/if}
			{/foreach}
		{/if}
		{if $obj.values.lists}
			{foreach key=key item=text from=$obj.values.lists}
					{if $text.caption!=''}{$text.caption} : {if $text.value}{$text.value}{else}не указано{/if}; {/if}
			{/foreach}
		{/if}	
		</p>
		{if $obj.my_object}
			{if $type.user_add}
				<p><a href="{$siteurl}{$urls[$type.add_cat]}?add=yes&id_type={$type.id_type}&mode=edit&id_object={$obj.id_object}">{$lang.objects.edit}</a></p>
			{/if}
		{/if}
	</div>
</div>