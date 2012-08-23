<table class="moduletable">
	<tr>
		<td class="module_single">
		{include file='admin/module_header.tpl.html'}
{if $modAction=="view"}
<div class="body">
	<div class="actionbutton" onclick="goTo('{$siteurl}admin/?module=languages&modAction=add');">Добавить язык</div>
</div>
<div class="body">
	<h2>Существующие языковые версии</h2>
<form action="{$siteurl}admin?module=languages" method="post" id="comments_form">
<table class="objects">
	<tr>					
		<td class="objects_header editable_header" width="90%"><span>Название версии</span></td>
		<td class="objects_header editable_header" width="10%" align="right">Удалить</td>
	</tr>
	{foreach key=key item=lng from=$languages}
	<tr class="{cycle values="objects_cell_light,objects_cell_bold"}" id="language{$lng.id_language}">
		<td class="editable"><span>{$lng.caption}</span><input type="text" name="lang[{$lng.id_language}]" value="{$lng.caption}" class="nonvisible"></td>
		<td align="center" class="actions">{if !$lng.default}
			<ul>
				<li><a href="javascript:void(0);" onclick="YesNo('{$lang.dialog.delete_language}','{$siteurl}admin/?module=languages&modAction=delete&id_language={$lng.id_language}');" title="{$lang.interface.delete_language} {$lng.caption}"><img src="{$img}admin/icons/delete.gif"></a></li>
			</ul>
		{/if}</td>
	</tr>
	{/foreach}
	<tr id="save_submit">
		<td colspan="5" align="center"><input type="submit" value="Сохранить" class="button"></td>
	</tr>
</table>
<input type="hidden" name="save" value="1">
</form>	
</div>
{elseif $modAction=="add"}
	{$form_html}
{/if}
		</td>
	</tr>
</table>