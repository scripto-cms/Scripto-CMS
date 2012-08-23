			<table class="moduletable">
				<tr>
					<td class="module_single">
						{include file='admin/module_header.tpl.html'}
					<div class="body">
{if $modAction=="view"}
<h2>Установленные модули</h2>
<table width="100%" cellpadding="1" cellspacing="2" id="cats">
<tr height="30">
	<td width="30%" class="shapka">Название модуля</td>
	<td width="10%" class="shapka">Разработчик</td>
	<td width="10%" class="shapka">Версия</td>
	<td width="20%" class="shapka" align="center">Управление</td>	
	<td width="10%" class="shapka" align="center">Выбор раздела</td>
	<td width="10%" class="shapka" align="center">Обновление</td>
	<td width="10%" class="shapka" align="center">Удаление</td>
</tr>
{if $modules}
{foreach key=key item=mod from=$modules}
{if $mod.installed}
<tr class="hover" id="module{$mod.name}">
	<td class="info" valign="middle" style="background:#e5e5e5;padding:10px;">{$mod.caption}</td>
	<td class="info" style="background:#e5e5e5;padding:10px;">{if $mod.author}{if $mod.url}<a href="{$mod.url}" target="_blank">{/if}{$mod.author}{if $mod.url}</a>{/if}{/if}</td>
	<td class="info" style="background:#e5e5e5;padding:10px;">{$mod.version}</td>
	<td class="info" style="background:#c8e7ff;padding:10px;" align="center"><input type="button" value="{$lang.interface.control_module}" onclick="goTo('{$siteurl}admin/?module=modules&modAction=settings&module_name={$mod.name}');" class="button"></td>	
	<td class="info" style="background:#fffcec;padding:10px;" align="center"><input type="button" value="{$lang.interface.category_module}" onclick="goTo('{$siteurl}admin/?module=modules&modAction=category_module&module_name={$mod.name}');"></td>
	<td class="info" style="background:#f0ffc8;padding:10px;" align="center"><input type="button" value="{$lang.interface.update_module}" onclick="YesNo('{$lang.dialog.update_module}','{$siteurl}admin/?module=modules&modAction=update&module_name={$mod.name}');"></td>		
	<td class="info" align="center" valign="middle" style="background:#ffcad9;padding:10px;"><input type="button" value="{$lang.interface.uninstall_module}" onclick="YesNo('{$lang.dialog.uninstall_module}','{$siteurl}admin/?module=modules&modAction=uninstall&module_name={$mod.name}');"></td>
{/if}
{/foreach}
{/if}
</table>

<h2>Неустановленные модули</h2>
<table width="100%" cellpadding="0" cellspacing="0" id="cats">
<tr height="30">
	<td width="30%" class="shapka">Название модуля</td>
	<td width="30%" class="shapka">Разработчик</td>
	<td width="30%" class="shapka">Версия</td>
	<td width="10%" class="shapka" align="right">Действия</td>
</tr>
{if $modules}
{foreach key=key item=mod from=$modules}
{if !$mod.installed}
<tr class="hover" id="module{$mod.name}">
	<td class="info" valign="middle" style="background:#e5e5e5;padding:10px;">{$mod.caption}</td>
	<td class="info" style="background:#e5e5e5;padding:10px;">{if $mod.author}{if $mod.url}<a href="{$mod.url}" target="_blank">{/if}{$mod.author}{if $mod.url}</a>{/if}{/if}</td>
	<td class="info" style="background:#e5e5e5;padding:10px;">{$mod.version}</td>
	<td class="info" align="center" valign="middle" style="background:#ededed;padding:10px;"><input type="button" value="{$lang.interface.install_module}" onclick="goTo('{$siteurl}admin/?module=modules&modAction=install&module_name={$mod.name}');"></td>
{/if}
{/foreach}
{/if}
</table>
{elseif $modAction=="category_module"}
{if $user_module.use_in_all_rubrics}
<b>Внимание! Данный модуль выводится во всех созданных Вами разделах!</b>
{else}
<p>Выберите раздел , в котором выводить модуль <b>{$user_module.caption}</b></p>
<form id="frm" action="{$siteurl}admin/?module=modules&modAction=category_module&module_name={$user_module.name}" method="post">
<table width="100%" id="mods">
<tr height="30">
	<td width="10%" class="shapka"></td>
	<td width="90%" class="shapka">Название раздела</td>
</tr>
{if $categories}
{foreach key=key item=category from=$categories}
<tr class="hover" id="cat{$category.id_category}">
	<td class="info" align="center">{if $user_module.use_in_one_rubric}<input type="radio" name="id_cat" value="{$category.id_category}" {if $category.module}checked{/if}>{else}<input type="checkbox" name="id_cat[{$category.id_category}]" {if $category.module}checked{/if}>{/if}</td>
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
{/if}
{elseif $modAction=="settings"}
{if $denied}
<h2>Доступ к модулю закрыт</h2>
<p>Вам необходимо связаться с администратором ресурса для предоставления Вам доступа.</p>
{else}
{$module_content}
{/if}
{/if}
		</div>
		</td>
	</tr>
</table>