			<table class="moduletable">
				<tr>
					<td class="module_single">
						{include file='admin/module_header.tpl.html'}
					<div class="body">
{if $modAction=="add"}
{$form_html}
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
		<td width="50%">Содержимое блока <b>{$block.caption}</b></td>
		<td width="50%" align="right"><input type="submit" value="Сохранить изменения"></td>
	</tr>
</table>
<input type="hidden" name="id_block" value="{$block.id_block}">
<input type="hidden" name="save" value="yes">
<input type="hidden" name="close" value="1">
{$editor}
</form>
{elseif $modAction=="module_block"}
{if $block_content}
{$block_content}
{else}
<p><b>Для данного блока настройки не предусмотрены!</b></p>
<p><a href="javascript:void(0);" onclick="parent.$.fancybox.close();">Закрыть окно</a></p>
{/if}
{elseif $modAction=="rss_block"}
{if $add_rss}
	<script>
		parent.tooltip('','RSS лента добавлена',3000);
	</script>
{/if}
{if $error_rss}
	<script>
		parent.tooltip('','Одно из полей заполнено неверно',3000);
	</script>
{/if}
{if $update_rss}
	<script>
		parent.tooltip('Данные сохранены','Обновлено {$n} лент , удалено {$del} лент',3000);
	</script>
{/if}
<div id="rss">
<b>Работаем с блоком {$block.caption}</b><br>
<h1>Добавить новую RSS ленту</h1>
<form action="{$siteurl}admin/?module=blocks&modAction=rss_block&mode=add&ajax=true" method="post">
<p><b>Название ленты:</b></p>
<p><input type="text" name="rss_caption" class="textbox" style="width:50%;"></p>
<p><b>Адрес ленты:</b></p>
<p><input type="text" name="rss_link" class="textbox" style="width:50%;"></p>
<input type="hidden" name="id_block" value="{$block.id_block}">
<p><input type="submit" value="Добавить новую ленту"></p>
</form>
<h1>Существующие rss ленты</h1>
<form action="{$siteurl}admin/?module=blocks&modAction=rss_block&ajax=true&id_block={$block.id_block}&mode=save" method="post">
<table width="100%" cellpadding="0" cellspacing="0" id="cats">
<tr height="30">
	<td width="30%" class="shapka">Название ленты</td>
	<td width="7%" class="shapka">Новостей</td>
	<td width="58%" class="shapka">Адрес</td>
	<td width="5%" class="shapka" align="right">Удалить</td>
</tr>
{if $rss}
{foreach key=key item=rs from=$rss}
<tr class="hover" id="rss{$text.id_text}">
	<td class="info" valign="top" style="background:#e5e5e5;padding:10px;"><input type="text" class="textbox" name="rss_caption[{$rs.id_rss}]" value="{$rs.rss_caption}" style="width:90%;"></td>
	<td class="info" style="background:#e5e5e5;padding:10px;"><input type="text" class="textbox" name="rss_number[{$rs.id_rss}]" value="{$rs.rss_number}" style="width:90%;"></td>
	<td class="info" style="background:#e5e5e5;padding:10px;"><input type="text" class="textbox" name="rss_link[{$rs.id_rss}]" value="{$rs.rss_link}" style="width:90%;"></td>
	<td class="info" align="center" valign="middle" style="background:#ededed;padding:10px;"><input type="checkbox" name="delete[{$rs.id_rss}]"></td>
<input type="hidden" name="idrss[{$rs.id_rss}]" value="{$rs.id_rss}">
{/foreach}
<tr>
	<td colspan="3" align="center" style="padding-top:10px;"><input type="submit" value="Сохранить" class="button"></td>
</tr>
{/if}
</table>
</form>
</div>
{elseif $modAction=="random_photo"}
<p>Выберите раздел , в котором выводить блок <b>{$block.caption}</b></p>
{if $save}
<script>
	parent.tooltip('','Данные сохранены',5000);
</script>
{/if}
<form id="frm" action="{$siteurl}admin/?module=blocks&modAction=random_photo&ajax=true&id_block={$block.id_block}" method="post">
<table width="100%" id="mods">
<tr height="30">
	<td width="10%" class="shapka"></td>
	<td width="90%" class="shapka">Название раздела</td>
</tr>
{if $categories}
{foreach key=key item=category from=$categories}
<tr class="hover" id="cat{$category.id_category}">
	<td class="info" align="center"><input type="checkbox" name="id_cat[{$category.id_category}]" {if $category.category_exist}checked{/if}></td>
	<td class="info" style="padding-left: {math equation="x * y" x="7" y=$category.level}px;"><a href="{$category.url}" target="_blank">{$category.caption}</a></td>
</tr>
{/foreach}
<input type="hidden" name="m_save" value="1">
<tr>
	<td colspan="2" align="center" style="padding-top:10px;"><input type="submit" value="Сохранить" class="button"></td>
</tr>
{/if}
</table>
</form>
{elseif $modAction=="text_block"}
{if $add_block}
	<script>
		parent.tooltip('','Текстовый блок добавлен успешно',3000);
	</script>
{/if}
{if $update_block}
	<script>
		parent.tooltip('Данные сохранены','Обновлено {$n} блоков , удалено {$del} блоков',3000);
	</script>
{/if}
<div id="texts">
<b>Работаем с блоком {$block.caption}</b><br>
<h1>Добавить новый текстовый блок</h1>
<form action="{$siteurl}admin/?module=blocks&modAction=text_block&mode=add&ajax=true" method="post">
<p><b>Название блока:</b></p>
<p><input type="text" name="caption" class="textbox" style="width:50%;"></p>
<p><b>Содержимое блока:</b></p>
<p><textarea name="content" style="width:50%;height:100px;"></textarea></p>
<input type="hidden" name="id_block" value="{$block.id_block}">
<input type="hidden" name="mode" value="add">
<p><input type="submit" class="button" value="Добавить новый блок"></p>
</form>
<h1>Существующие текстовые блоки</h1>
<form action="{$siteurl}admin/?module=blocks&modAction=text_block&ajax=true&id_block={$block.id_block}&mode=save" method="post">
<table width="100%" cellpadding="0" cellspacing="0" id="cats">
<tr height="30">
	<td width="35%" class="shapka">Название</td>
	<td width="60%" class="shapka">Текст</td>
	<td width="5%" class="shapka" align="right">Удалить</td>
</tr>
{if $texts}
{foreach key=key item=text from=$texts}
<tr class="hover" id="text{$text.id_text}">
	<td class="info" valign="top" style="background:#e5e5e5;padding:10px;"><input type="text" class="textbox" name="text[{$text.id_text}]" value="{$text.caption}" style="width:90%;"></td>
	<td class="info" style="background:#e5e5e5;padding:10px;"><textarea name="content[{$text.id_text}]" style="width:95%;height:100px;">{$text.content}</textarea></td>
	<td class="info" align="center" valign="middle" style="background:#ededed;padding:10px;"><input type="checkbox" name="delete[{$text.id_text}]"></td>
<input type="hidden" name="idtext[{$text.id_text}]" value="{$text.id_text}">
{/foreach}
<tr>
	<td colspan="3" align="center" style="padding-top:10px;"><input type="submit" value="Сохранить" class="button"></td>
</tr>
{/if}
</table>
</form>
</div>
{elseif $modAction=="view" || $modAction=="save"}
<div class="body">
	<div class="actionbutton" onclick="goTo('{$siteurl}admin/?module=blocks&modAction=add');">Создать новый блок</p>
</div>
<div class="body">
{if $blocks}
<form id="frm" method="post">
<table class="objects">
<tr>
	<td class="objects_header editable_header" width="25%"><span>Название</span></td>
	<td class="objects_header" width="1%"></td>
	<td class="objects_header editable_header" width="14%"><span>Идентификатор</span></td>
	<td class="objects_header" width="8%">Создан</td>
	<td class="objects_header editable_header" width="5%"><span>Сортировка</span></td>
	<td class="objects_header editable_header" width="15%"><span>Тип блока</span></td>
	<td class="objects_header editable_header" width="12%"><span>Кол-во объектов</span></td>
	<td class="objects_header" width="5%">Видимость</td>
	<td class="objects_header" width="15%" align="right">Действия</td>
</tr>
{foreach key=key item=block from=$blocks}
<tr class="{cycle values="objects_cell_light,objects_cell_bold"}" id="block{$block.id_block}">
	<td class="editable"><span>{$block.caption}</span><input type="text" name="caption[{$block.id_block}]" value="{$block.caption}" class="nonvisible"></td>
	<td align="center"><a href="{$siteurl}admin/?module=blocks&modAction=quickedit&id_block={$block.id_block}&ajax=yes" class="editor"><img src="{$admin_icons}quickedit.png" border="0"></a></td>
	<td class="editable"><span>{$block.ident}</span><input type="text" name="ident[{$block.id_block}]" value="{$block.ident}" class="nonvisible"></td>
	<td>{$block.create_print}</td>	
	<td class="editable" align="center"><span>{$block.sort}</span><input type="text" name="sort[{$block.id_block}]" value="{$block.sort}" class="nonvisible"></td>
	<td class="editable"><span>{$block.type_name}</span><select name="blocktype[{$block.id_block}]" class="nonvisible">{foreach key=key item=btype from=$block_types}<option value="{$btype.id}" {if $block.id_type==$btype.id}selected{/if}>{$btype.name}</option>{/foreach}</select></td>
	<td class="editable" align="center"><span>{$block.number_objects}</span><input type="text" name="numbers[{$block.id_block}]" value="{$block.number_objects}" style="width:30%;" class="nonvisible"></td>
	<td align="center"><input type="checkbox" name="visible[{$block.id_block}]" {if $block.visible}checked{/if} class="checkbox" numb="0"></td>
	<td class="actions" align="right">		
		<ul>
			<li><a href="{$siteurl}admin/?module=blocks&modAction=add&mode=edit&id_block={$block.id_block}" title="{$lang.interface.edit_block} {$block.caption}"><img src="{$img}admin/icons/edit.gif" border="0"></a></li>
			<li><a href="javascript:void(0);" onclick="YesNo('{$lang.dialog.delete_block}','{$siteurl}admin/?module=blocks&modAction=delete&id_block={$block.id_block}');" title="{$lang.interface.delete_block} {$block.caption}"><img src="{$img}admin/icons/delete.gif"  border="0"></a></li>
			{if $block.type.type=="random" || $block.type.type=="texts"}
			<li><a href="{$siteurl}admin/?module=blocks&modAction=text_block&mode=view&id_block={$block.id_block}&ajax=yes" title="{$lang.interface.text_block} {$block.caption}" class="dialog"><img src="{$img}admin/icons/cube_blue.gif" alt="{$lang.interface.text_block} {$block.caption}"></a></li>			
			{/if}
			{if $block.type.type=="rss"}
			<li><a href="{$siteurl}admin/?module=blocks&modAction=rss_block&mode=view&id_block={$block.id_block}&ajax=yes" title="{$lang.interface.rss_block} {$block.caption}" class="dialog"><img src="{$img}admin/icons/cube_blue.gif" alt="{$lang.interface.rss_block} {$block.caption}"></a></li>			
			{/if}		
			{if $block.type.type=="random_photo"}
			<li><a href="{$siteurl}admin/?module=blocks&modAction=random_photo&id_block={$block.id_block}&ajax=yes" class="dialog" title="{$lang.interface.random_photo_block} {$block.caption}"><img src="{$img}admin/icons/cube_blue.gif" alt="{$lang.interface.random_photo_block} {$block.caption}"></a></li>			
			{/if}				
			{if $block.type.module!=""}
			<li><a href="{$siteurl}admin/?module=blocks&modAction=module_block&id_block={$block.id_block}&ajax=yes&mod_name={$block.type.module}" title="{$lang.interface.module_block} {$block.caption}" class="dialog"><img src="{$img}admin/icons/cube_blue.gif" alt="{$lang.interface.module_block} {$block.caption}"></a></li>			
			{/if}					
		</ul>
	</td>
</tr>
<input type="hidden" name="idblock[{$block.id_block}]" value="{$block.id_block}">
{/foreach}
<tr>
	<td colspan="7"></td>
	<td class="selectall" align="center"><input type="checkbox" numb="0"></td>
	<td></td>
</tr>
<tr id="save_submit">
	<td colspan="8" align="center" style="padding-top:10px;"><input type="submit" value="Сохранить" class="button"></td>
</tr>
</table>
<input type="hidden" name="modAction" value="save">
<input type="hidden" name="module" value="blocks">
</form>
{/if}
{/if}
</div>
		</div>
		</td>
	</tr>
</table>