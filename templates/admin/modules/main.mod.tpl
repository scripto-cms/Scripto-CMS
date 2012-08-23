{include file='admin/module_header.tpl.html'}
<div class="body">
{if $modAction=="edit"}
	<h2>Добавить элемент управления</h2>
	<form action="{$siteurl}admin?modAction=edit" method="post">
	<table width="100%">
		<tr>
			<td class="objects_header" width="30%">Название</td>
			<td class="objects_header" width="20%">URL</td>
			<td class="objects_header" width="30%">Тип</td>
			<td class="objects_header" width="20%">Способ открытия</td>
		</tr>
		<tr height="30">
			<td><input type="text" name="caption"></td>
			<td><input type="text" name="url"></td>
			<td><select name="button_type">{foreach key=key item=type from=$config.button_types}<option value="{$type.id}">{$type.name}</option>{/foreach}</select></td>
			<td><select name="href_type">{foreach key=key item=type from=$config.open_types}<option value="{$type.id}">{$type.name}</option>{/foreach}</select></td>
		</tr>
		<tr>
			<td colspan="4" align="center" class="bigbutton"><input type="submit" value="Добавить"></td>
		</tr>
	</table>
	<input type="hidden" name="add" value="1">
	</form>
	{if $buttons}
	<h2>Существующие элементы управления</h2>
	<form action="{$siteurl}admin?modAction=edit" method="post">
	<table width="100%">
		<tr>
			<td class="objects_header editable_header" width="20%"><span>Название</span></td>
			<td class="objects_header editable_header" width="20%"><span>URL</span></td>
			<td class="objects_header editable_header" width="20%"><span>Тип</span></td>
			<td class="objects_header editable_header" width="20%"><span>Способ открытия</span></td>
			<td class="objects_header editable_header" width="10%"><span>Сортировка</span></td>
			<td class="objects_header editable_header" width="10%"><span>Удалить</span></td>
		</tr>
		{foreach key=key item=button from=$buttons}
		<tr height="30" class="{cycle values="objects_cell_light,objects_cell_bold"}" id="button{$button.id_button}">
			<td class="editable"><span>{$button.caption}</span><input type="text" name="caption[{$button.id_button}]" value="{$button.caption}" class="nonvisible"></td>
			<td class="editable"><span>{$button.url|truncate:50:'...'}</span><input type="text" name="url[{$button.id_button}]" value="{$button.url}" class="nonvisible"></td>
			<td><select name="button_type[{$button.id_button}]">{foreach key=key item=type from=$config.button_types}<option value="{$type.id}" {if $type.id==$button.type}selected{/if}>{$type.name}</option>{/foreach}</select></td>
			<td class="editable"><select name="href_type[{$button.id_button}]">{foreach key=key item=type from=$config.open_types}<option value="{$type.id}" {if $type.id==$button.open_type}selected{/if}>{$type.name}</option>{/foreach}</select></td>
			<td class="editable" align="center"><span>{$button.sort}</span><input type="text" name="sort[{$button.id_button}]" value="{$button.sort}" class="nonvisible"></td>
			<td align="center"><input type="checkbox" name="del[{$button.id_button}]" class="deletecheckbox" numb="0"></td>
		</tr>
		<input type="hidden" name="idbutton[{$button.id_button}]" value="{$button.id_button}">
		{/foreach}
		<input type="hidden" name="save" value=1>
			<tr>
				<td colspan="5"></td>
				<td class="selectall" align="center"><input type="checkbox" class="deletecheckbox" numb="0"></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="6" align="center" class="bigbutton"><input type="submit" value="Сохранить" class="button"></td>
			</tr>	
	</table>
	</form>
	{else}
	<p>Вы не создали ни одного элемента управления</p>
	{/if}
{elseif $modAction=="requirements"}
		<h2>Отчет об установленных необходимых библиотеках PHP и Apache</h2>
		<table width="100%">
		<tr>
			<td width="80%" class="header"><b>Библиотека</b></td>
			<td width="20%" class="header" align="center"><b>Статус</b></td>
		</tr>
			{foreach key=key item=lib from=$report}
				<tr height="30" class="{cycle values="objects_cell_light,objects_cell_bold"}">
					<td>{$lib.value}</td>
					<td align="center">{if $lib.install}<img src="/images/admin/ok.png" alt="Установлено">{else}<img src="/images/admin/error.png" alt="Не установлено">{/if}</td>
				</tr>
			{/foreach}
		</table>
		<h2>Проверка рекомендуемых настроек сервера</h2>
		<p>В данной таблице приведены рекомендуемые настройки сервера, настройки Вашего сервера могут отличаться от рекомендуемых.</p>
		<table width="100%" class="objects">
		<tr>
			<td width="80%" class="header"><b>Параметр</b></td>
			<td width="20%" class="header" align="center"><b>Статус</b></td>
		</tr>
			{foreach key=key item=lb from=$setups}
				<tr height="30" class="{cycle values="objects_cell_light,objects_cell_bold"}">
					<td>{$lb.value}</td>
					<td align="center">{if $lb.install}<img src="/images/admin/ok.png" alt="Установлено">{else}<img src="/images/admin/alarm.png" alt="Не установлено">{/if}</td>
				</tr>
			{/foreach}		
		</table>
		<h2>Проверка установки необходимых прав на файлы и папки</h2>
		<p>В данной таблице приведен отчет о проверки доступности файлов и папок <b>на запись</b>, если некоторые файлы или папки недоступны для записи, Вам необходимо вручную установить права, достаточные для записи (обычно это 755,775).</p>
		<table width="100%" class="objects">
		<tr>
			<td width="80%" class="header"><b>Файл\папка</b></td>
			<td width="20%" class="header" align="center"><b>Статус</b></td>
		</tr>
			{foreach key=key item=file from=$files}
				<tr height="30" class="{cycle values="objects_cell_light,objects_cell_bold"}">
					<td>{$file.value}</td>
					<td align="center">{if $file.install}<img src="/images/admin/ok.png" alt="Установлено">{else}<img src="/images/admin/error.png" alt="Не установлено">{/if}</td>
				</tr>
			{/foreach}		
		</table>
{else}
<table class="moduletable">
	<tr>
		<td class="module_left">
			<h3>Новости Scripto CMS</h3>
			{if $scripto_news}
			{$scripto_news}
			{else}
			<div class="news_item">
				<h3>Ошибка связи</h3>
				<p>К сожалению на данный момент сайт Scripto CMS не доступен</p>
			</div>
			{/if}
			<h2>Проверить совместимость хостинга</h2>
			<p>Вы можете проверить удовлетворяют ли текущие настройки хостинга требованиям скрипта, нажав на кнопку "проверить совместимость".</p>
			<p><div class="actionbutton"><a href="{$siteurl}admin/?modAction=requirements">Проверить совместимость</a></div></p>
		</td>
		<td class="module_right">
			<p class="module_info"><a href="{$siteurl}admin/?modAction=edit">редактировать элементы</a></p>
			<div class="body">
				{if $buttons}
				{foreach key=key item=button from=$buttons}
				{if $button.type=="orangebutton"}
					<div class="new_button orange">
				{/if}
				{if $button.type=="yellowbutton"}
					<div class="new_button yellow">
				{/if}					
				{if $button.type=="webbutton"}
					<div class="new_button web20">
				{/if}
				{if $button.type=="blackbutton"}
					<div class="new_button black">
				{/if}								
				{if $button.type=="bluebutton"}
					<div class="new_button blue">
				{/if}				
				{if $button.type=="greenbutton"}
					<div class="new_button green">
				{/if}
				{if $button.type=="separator"}
					<div class="separator">
				{/if}
				{if $button.type=="button"}
					<div class="actionbutton">
				{/if}
					{if $button.url}
						{if $button.open_type==0}<a href="{$button.url}">{$button.caption}</a>{/if}
						{if $button.open_type==1}<a href="{$button.url}" target="_blank">{$button.caption}</a>{/if}
						{if $button.open_type==2}<a href="{$button.url}" class="dialog">{$button.caption}</a>{/if}
					{else}
						{$button.caption}
					{/if}
					</div>
				{/foreach}
				{/if}
			</div>
		</td>
	</tr>
</table>
{/if}
</div>