{foreach key=key item=category from=$categories}
{if $category.id_sub_category==$idcat}
{if $category.level>=1 && !$category.selected} 
{assign var="newvisible" value=false}
{else}
{assign var="newvisible" value=true}
{/if}
<div class="divblock {if !$visible}invisible_category{else}category_float{/if}" parent="{$idcat}" idcat="{$category.id_category}">
<table class="objects">
<tr class="{cycle values="objects_cell_light,objects_cell_bold"}{if $category.selected} selected_category{/if}" id="cat{$category.id_category}" idcat="{$category.id_sub_category}">
	<td width="2%">{if $category.level==0}<input type="radio" name="main_page" value="{$category.id_category}" {if $category.main_page}checked{/if}>{/if}</td>
	<td class="editable" width="40%" style="padding-left: {math equation="x * y" x="16" y=$category.level+1}px;">{if $category.categories>0}<div class="plusme">{if $newvisible}<img src="{$admin_icons}minus.png"  parent="{$idcat}" idcat="{$category.id_category}" mode="1" border="0">{else}<img src="{$admin_icons}plus.png"  parent="{$idcat}" idcat="{$category.id_category}" mode="0" border="0">{/if}</div>{/if}<span myid="{$category.id_category}">{if $category.caption==''}--{else}{$category.caption}{/if}</span><input type="text" name="caption[{$category.id_category}]" value="{$category.caption}" class="nonvisible"></td>
	<td class="editable" width="20%"><span>{$category.ident}</span><input type="text" name="ident[{$category.id_category}]" value="{$category.ident}" class="nonvisible" style="width:80%;"><a href="{$siteurl}{$category.ident}" target="_blank" title="Открыть рубрику"><img src="{$admin_icons}golink.png" border="0" class="minipic"></a></td>
	<td class="editable" width="5%"><span>{$category.category_type}</span><select name="type[{$category.id_category}]" class="nonvisible">{foreach key=key item=type from=$config.content_type}<option value="{$type.ident}" {if $category.category_type==$type.ident}selected{/if}>{$type.name}</option>{/foreach}</select></td>
	<td class="editable" width="7%" align="center" style="padding-left: {math equation="x * y" x="3" y=$category.level}px;"><span>{$category.sort}</span><input type="text" size="3" value="{$category.sort}" name="sort[{$category.id_category}]" class="nonvisible"></td>
	<td align="center" width="1%"><a href="{$siteurl}admin/?module=catalog&modAction=quickedit&id_cat={$category.id_category}&ajax=yes" class="editor"><img src="{$admin_icons}quickedit.png" border="0"></a></td>
	<td align="center" width="5%">
	<input type="checkbox" class="checkbox" numb="0" name="visible[{$category.id_category}]" {if $category.visible}checked{/if}>
	</td>
	<td width="5%">{$category.create_print}</td>
	<td width="5%" class="editable" align="center"><span>{$category.position}</span><select name="menu_type[{$category.id_category}]" class="nonvisible">{foreach key=key item=type from=$config.menu_type}<option value="{$key}" {if $category.position==$key}selected{/if}>{$type}</option>{/foreach}</select></td>
	<td width="10%" class="actions" align="right" nowrap>
		<ul>
			<li><a href="javascript:void(0);" onclick="promptUrl('{$lang.interface.add_blank_message}','{$siteurl}admin/?module=catalog&modAction=add_blank&id_cat={$category.id_category}','number');" title="{$lang.interface.add_blank_caption}"><img src="{$img}admin/icons/add_blank.png"></a></li>		
			<li><a href="{$siteurl}admin/?module=catalog&modAction=add&id_cat={$category.id_category}" title="{$lang.interface.add_category}"><img src="{$img}admin/icons/add_16.gif"></a></li>		
			<li><a href="{$siteurl}admin/?module=catalog&modAction=add&mode=edit&id_cat1={$category.id_category}" title="{$lang.interface.edit_category} {$category.caption}"><img src="{$img}admin/icons/edit.gif"></a></li>
			<li><a href="javascript:void(0);" onclick="YesNo('{$lang.dialog.delete_category}','{$siteurl}admin/?module=catalog&modAction=delete&id_cat={$category.id_category}');" title="{$lang.interface.delete_category} {$category.caption}"><img src="{$img}admin/icons/delete.gif"></a></li>
		</ul>
	</td>
</tr>
<input type="hidden" name="idcat[{$category.id_category}]" value="{$category.id_category}">
</table>
{if $category.categories>0}
{include file="admin/modules/print_catalog.tpl" categories=$categories idcat=$category.id_category visible=$newvisible}
{/if}
</div>
{/if}
{/foreach}