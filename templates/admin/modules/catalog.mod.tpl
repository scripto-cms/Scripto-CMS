			<table class="moduletable">
				<tr>
					<td class="module_single">
						{include file='admin/module_header.tpl.html'}
					<div class="body">
{if $modAction=="add"}
{if $mod_name}
<p>Раздел успешно создан!</p>
<script>
parent.goTo('{$ref}&id_category={$id_cat}');
</script>
{else}
{$form_html}
{/if}
{elseif $modAction=="quickedit"}
{if $save}
 <script>
 	parent.tooltip('','Изменения сохранены!',3000);
 </script>
{/if}
{if $close}
<script>
{literal}
	parent.show_close_dialog=false;
	parent.$.fancybox.close();
{/literal}
</script>
{/if}
<form action="" name="frm" method="post">
<table width="100%" cellpadding="0" cellspacing="0" class="whiteheader">
	<tr>
		<td width="50%">Содержимое раздела <b>{$category.caption}</b></td>
		<td width="50%" align="right"><input type="submit" value="Сохранить изменения"></td>
	</tr>
</table>
<input type="hidden" name="id_cat" value="{$category.id_category}">
<input type="hidden" name="save" value="yes">
<input type="hidden" name="close" value="1">
{$editor}
</form>
{elseif $modAction=="view" || $modAction=="save"}
<div class="body"><div class="actionbutton" onclick="goTo('{$siteurl}admin/?module=catalog&modAction=add');">Создать новый раздел</div></div>
{if $categories}
<div class="body">
<form id="frm" method="post">
<table class="objects">
<tr>
	<td colspan="9" align="left" id="sort">Сортировать разделы:<br> <b>по названию</b> (
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=caption"><img src="{$img}admin/icons/up.gif" border="0"></a>
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=caption&desc=true"><img src="{$img}admin/icons/down.gif" border="0"></a>
	)
	<b>по сортировке</b> (
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=sort"><img src="{$img}admin/icons/up.gif" border="0"></a>
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=sort&desc=true"><img src="{$img}admin/icons/down.gif" border="0"></a>	
	)
	<b>по дате</b>
 	(
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=date"><img src="{$img}admin/icons/up.gif" border="0"></a>
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=date&desc=true"><img src="{$img}admin/icons/down.gif" border="0"></a>	
	)
	<b>по видимости</b>
 	(
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=visible"><img src="{$img}admin/icons/up.gif" border="0"></a>
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=visible&desc=true"><img src="{$img}admin/icons/down.gif" border="0"></a>	
	)
	<b>по типу содержимого</b>
 	(
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=type"><img src="{$img}admin/icons/up.gif" border="0"></a>
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=type&desc=true"><img src="{$img}admin/icons/down.gif" border="0"></a>	
	)
	<b>по расположению в меню</b>
 	(
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=position"><img src="{$img}admin/icons/up.gif" border="0"></a>
	<a href="{$siteurl}admin/?module=catalog&modAction=view&order=position&desc=true"><img src="{$img}admin/icons/down.gif" border="0"></a>	
	)			
	</td>
</tr>								
<tr>
	<td class="objects_header" width="2%"></td>
	<td class="objects_header editable_header" width="40%"><span>Название</span></td>
	<td class="objects_header editable_header" width="20%"><span>ЧПУ</span></td>
	<td class="objects_header editable_header" width="5%"><span>Тип</span></td>
	<td class="objects_header editable_header" width="7%"><span>Сортировка</span></td>
	<td class="objects_header" width="1%"></td>
	<td class="objects_header" width="5%">Видимость</td>
	<td class="objects_header" width="5%">Создан</td>
	<td class="objects_header editable_header" width="5%"><span>Меню</span></td>
	<td class="objects_header" width="10%" align="right">Действия</td>
</tr>
<tr>
<td colspan="10">
{include file="admin/modules/print_catalog.tpl" categories=$categories idcat=0 visible=true}
</td>
</tr>
<tr>
	<td colspan="6"></td>
	<td class="selectall" align="center"><input type="checkbox" numb="0"></td>
	<td colspan="3"></td>
</tr>
<tr id="save_submit">
	<td colspan="9" align="center" style="padding-top:10px;"><input type="submit" value="Сохранить" class="button"></td>
</tr>
</table>
<input type="hidden" name="modAction" value="save">
<input type="hidden" name="module" value="catalog">
<input type="hidden" name="order" value="{$order}">
{if $desc}
<input type="hidden" name="desc" value="true">
{/if}
</form>
</div>
{/if}
{/if}
		</div>
		</td>
	</tr>
</table>