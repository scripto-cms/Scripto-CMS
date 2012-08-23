{foreach key=key item=category from=$categories}
{if $category.id_sub_category==$idcat}
{if $category.level>=1 && !$category.selected} 
{assign var="newvisible" value=false}
{else}
{assign var="newvisible" value=true}
{/if}
<ul class="divblock {if !$visible}invisible_category{else}category_float{/if}" {if $padding} style="padding-top:2px;"{/if} parent="{$idcat}" idcat="{$category.id_category}">
	{if $mode=="seenew"}
	<li style="padding-left: 7px;{if $category.categories>0}padding-top:3px;{/if}">{if $category.categories>0}<div class="plusme">{if $newvisible}<img src="{$admin_icons}minus.png"  parent="{$idcat}" idcat="{$category.id_category}" mode="1" border="0">{else}<img src="{$admin_icons}plus.png"  parent="{$idcat}" idcat="{$category.id_category}" mode="0" border="0">{/if}</div>{/if}<a href="javascript:void(0);"  {if $category.main_page}style="font-weight:bold;"{/if} class="copy_objects{if $category.visible==0} nvisible{/if}" idcat="{$category.id_category}">{$category.caption}</a> [{if $this_cat.id_category==$category.id_category}<b>מבתוךעû</b>{else}<a href="{$siteurl}admin/?module=objects&modAction=edit&id_cat={$category.id_category}">מבתוךעû</a>{/if}]
	{elseif $mode=="edit"}
	<li style="padding-left: 7px;{if $category.categories>0}padding-top:3px;{/if}">{if $category.categories>0}<div class="plusme">{if $newvisible}<img src="{$admin_icons}minus.png"  parent="{$idcat}" idcat="{$category.id_category}" mode="1" border="0">{else}<img src="{$admin_icons}plus.png"  parent="{$idcat}" idcat="{$category.id_category}" mode="0" border="0">{/if}</div>{/if}<a href="javascript:void(0);"  {if $category.main_page}style="font-weight:bold;"{/if} class="move_objects{if $category.visible==0} nvisible{/if}" idcat="{$category.id_category}">{$category.caption}</a> [{if $this_cat.id_category==$category.id_category}<b>מבתוךעû</b>{else}<a href="{$siteurl}admin/?module=objects&modAction=edit&id_cat={$category.id_category}">מבתוךעû</a>{/if}]	
	{/if}
	{if $category.categories>0}
	{include file="admin/modules/print_gallery.tpl" padding="true" categories=$categories mode=$mode idcat=$category.id_category visible=$newvisible}
	{/if}
	</li>
</ul>
{/if}
{/foreach}