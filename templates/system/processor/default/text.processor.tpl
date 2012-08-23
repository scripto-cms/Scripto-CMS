{if $page.is_registered}
	{if $authorized || $not_authorized}
		{if $authorized}
			{assign var="may_see" value=true}
		{else}
			{assign var="may_see" value=false}
		{/if}
	{else}
		{assign var="may_see" value=true}
	{/if}
{else}
	{assign var="may_see" value=true}
{/if}
{if $may_see}
{if $page.pages}
{$page.content_page}
{foreach from=$page.pages key=k item=pg}
{if $pg==$page.current_page}
<b>{$pg+1}</b>&nbsp;
{else}
<a href="{$page.url}?category_page={$pg}">{$pg+1}</a>&nbsp;
{/if}
{/foreach}
{else}
{if $page.sep}
{assign var="v" value=0}
{foreach from=$page.sep key=k item=sep}
{if $v==0}
{$sep}
{assign var="v" value=1}
{else}
				<div class="tooltip">
					<div class="tooltip_attention"><img src="/img/tooltip_attention.jpg" border="0"></div>
					<div class="tooltip_body">{$sep}</div>
				</div>
{assign var="v" value=0}				
{/if}
{/foreach}
{else}
{$page.content_page}
{/if}
{/if}
{if $page.tags}
{$lang.user.tags_of_category}: {$page.tags}
{/if}
{else}
{$lang.page_error}
{/if}