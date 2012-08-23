{if $modAction=="addreminder" || $modAction=="addnote"}
<table class="moduletable">
	<tr>
		<td class="module_single">
		{include file='admin/module_header.tpl.html'}
		<div class="body">
		{$form_html}
		</div>
		</td>
	</tr>
</table>
{elseif $modAction=="viewnote"}
{if $note}
<h1>Просмотр заметки {$note.caption}</h1>
<div class="body">
{$note.content}
</div>
{else}
<p>Неверно задан идентификатор заметки</p>
{/if}
{else}
{include file='admin/module_header.tpl.html'}
<div class="body">
<table class="moduletable">
	<tr>
		<td class="module_left">
			<ul>
				<li>{if $modAction=="view" || $modAction=="addreminder"}<b>Напоминания</b>{else}<a href="{$siteurl}admin?module=notes">Напоминания</a>{/if}</li>
				<li>{if $modAction=="affairs"}<b>Дела</b>{else}<a href="{$siteurl}admin?module=notes&modAction=affairs">Дела</a>{/if}</li>
				<li>{if $modAction=="notes"}<b>Заметки</b>{else}<a href="{$siteurl}admin?module=notes&modAction=notes">Заметки</a>{/if}</li>
			</ul>
		</td>
		<td class="module_right">
		<div class="body">
			{if $modAction=="view"}
			<h2>Напоминания</h2>
			<div class="body">
				<div class="actionbutton" onclick="javascript:goTo('{$siteurl}admin/?module=notes&modAction=addreminder');">Добавить напоминание</div>
			</div>	
			<div class="body">		
			{if $reminders}
			<form action="{$siteurl}admin" method="post">
			<table class="objects">
			<tr>
				<td class="objects_header editable_header" width="40%"><span>Тема</span></td>
				<td class="objects_header editable_header" width="25%"><span>Дата</span></td>
				<td class="objects_header editable_header" width="25%"><span>Время</span></td>
				<td class="objects_header editable_header" width="5%" align="right">Удалить</td>
				<td class="objects_header" width="5%" align="right">Действия</td>
			</tr>
			{foreach key=key item=reminder from=$reminders}
			<tr class="{cycle values="objects_cell_light,objects_cell_bold"}" id="reminder{$reminder.id_reminder}">
				<td class="editable"><span>{$reminder.subject}</span><input type="text" name="subject[{$reminder.id_reminder}]" value="{$reminder.subject}" class="nonvisible"></td>
				<td class="editable"><span>{$reminder.date_print}</span><select name="date_day[{$reminder.id_reminder}]" class="nonvisible date_list">{foreach key=key item=day from=$days}<option value="{$day}" {if $reminder.date_day==$day}selected{/if}>{if $day<10}0{/if}{$day}</option>{/foreach}</select><select name="date_month[{$reminder.id_reminder}]" class="nonvisible date_list">{foreach key=key item=month from=$months}<option value="{$month}" {if $reminder.date_month==$month}selected{/if}>{if $month<10}0{/if}{$month}</option>{/foreach}</select><select name="date_year[{$reminder.id_reminder}]" class="nonvisible year_list">{foreach key=key item=year from=$years}<option value="{$year}" {if $reminder.date_year==$year}selected{/if}>{$year}</option>{/foreach}</select></td>
				<td class="editable"><span>{$reminder.time_print}</span><select name="time_hour[{$reminder.id_reminder}]" class="nonvisible date_list">{foreach key=key item=hour from=$hours}<option value="{$hour}" {if $reminder.time_hour==$hour}selected{/if}>{if $hour<10}0{/if}{$hour}</option>{/foreach}</select><select name="time_minute[{$reminder.id_reminder}]" class="nonvisible date_list">{foreach key=key item=minute from=$minutes}<option value="{$minute}" {if $reminder.time_minute==$minute}selected{/if}>{if $minute<10}0{/if}{$minute}</option>{/foreach}</select></td>
				<td align="center"><input type="checkbox" name="del[{$reminder.id_reminder}]" class="deletecheckbox" numb="0"></td>
				<td class="actions" align="right">		
					<ul>
						<li><a href="{$siteurl}admin/?module=notes&modAction=addreminder&mode=edit&id_reminder={$reminder.id_reminder}" title="{$lang.interface.edit_reminder} {$reminder.subject}"><img src="{$img}admin/icons/edit.gif" border="0"></a></li>
					</ul>
				</td>
			</tr>
			<input type="hidden" name="idreminder[{$reminder.id_reminder}]" value="{$reminder.id_reminder}">
			{/foreach}
			<tr>
				<td colspan="3"></td>
				<td class="selectall" align="center"><input type="checkbox" class="deletecheckbox" numb="0"></td>
				<td></td>
			</tr>
			<tr id="save_submit">
				<td colspan="5" align="center" style="padding-top:10px;"><input type="submit" value="Сохранить" class="button"></td>
			</tr>
			</table>
			<input type="hidden" name="modAction" value="save">
			<input type="hidden" name="module" value="notes">
			</form>
			{else}
				<p>На данный момент напоминаний не создано</p>
			{/if}
			</div>
		{elseif $modAction=="notes"}
			<h2>Заметки</h2>
			<div class="body">
				<div class="actionbutton" onclick="javascript:goTo('{$siteurl}admin/?module=notes&modAction=addnote');">Добавить заметку</div>
			</div>
			<h2>Существующие заметки</h2>
			<div class="body">
			{if $notes}
			<table class="objects">
			<tr>
				<td class="objects_header editable_header" width="55%">Название заметки</td>
				<td class="objects_header editable_header" width="15%">Дата создания</td>
				<td class="objects_header editable_header" width="25%">Просмотреть заметку</td>
				<td class="objects_header editable_header" width="5%" align="right">Действия</td>
			</tr>
			{foreach key=key item=note from=$notes}
			<tr class="{cycle values="objects_cell_light,objects_cell_bold"}" id="note{$note.id_note}">
				<td>{$note.caption}</td>
				<td>{$note.create_print}</td>
				<td align="center"><a href="{$siteurl}admin/?module=notes&modAction=viewnote&id_note={$note.id_note}&ajax=yes" class="dialog" title="{$lang.interface.view_note} {$note.caption}"><img src="/images/admin/icons/view.png" alt=""></a></td>
				<td class="actions" align="right">		
					<ul>
						<li><a href="{$siteurl}admin/?module=notes&modAction=addnote&mode=edit&id_note={$note.id_note}" title="{$lang.interface.edit_note} {$note.caption}"><img src="{$img}admin/icons/edit.gif" border="0"></a></li>
						<li><a href="javascript:void(0);" onclick="YesNo('{$lang.dialog.delete_note}','{$siteurl}admin/?module=notes&modAction=deletenote&id_note={$note.id_note}');" title="{$lang.interface.delete_note} {$note.caption}"><img src="{$img}admin/icons/delete.gif"></a></li>
					</ul>
				</td>
			</tr>			
			{/foreach}
			</table>			
			{else}
				<p>Ни одной заметки не создано</p>
			{/if}
			</div>
		{else}
			<h2>Новое дело</h2>
			<div class="body">
				<table width="100%">
					<tr>
						<td class="objects_header" width="55%">Описание (максимум 110 символов)</td>
						<td class="objects_header" width="25%">Важность</td>
						<td class="objects_header" width="20%">Действия</td>
					</tr>
					<tr height="50">
						<td valign="top"><textarea id="delo" class="n100"></textarea></td>
						<td valign="top" class="biglist">
						<select id="vazhn">
						<option value="0">Очень важное</option>
						<option value="1">Важное</option>
						<option value="2" selected>Среднее</option>
						<option value="3">Когда нибудь</option>
						</select>
						</td>
						<td valign="top" align="center" class="bigbutton"><input type="button" id="createAffair" value="добавить"></td>
					</tr>
					<tr height="15">
						<td colspan="3"><div id="symbols"></div></td>
					</tr>
				</table>
			</div>	
			<h2>Дела</h2>
			<div class="body" id="affairs">
			{if !$affairs}
				<p id="clear">На данный момент не создано ни одного дела</p>
			{/if}				
				{foreach key=key item=vzhn from=$vazhn}
				<div class="process process{$vzhn}" vazhn="{$vzhn}">
					{foreach key=key2 item=aff from=$affairs[$vzhn]}
						<div id="affair{$aff.id_process}">
							<table>
								<tr>
									<td width="45%" class="proc">{$aff.content}</td>
									<td width="10%" align="center"><input type="button" class="del_btn" id_process="{$aff.id_process}" value="удалить"></td>
									<td width="20%" align="center">{$aff.create_print}</td>
									<td width="25%" align="right" class="bigbutton" id="btn_{$aff.id_process}">
									{if $aff.done}
										<p>Выполнено <b>{$aff.done_print}</b></p>
									{else}
										<input type="button" class="subm_btn" id_process="{$aff.id_process}" value="Готово!">
									{/if}
									</td>
								</tr>
							</table>
						</div>
					{/foreach}
				</div>
				{/foreach}	
			</div>
		{/if}
		</div>
		</td>
	</tr>
</table>
</div>
{/if}