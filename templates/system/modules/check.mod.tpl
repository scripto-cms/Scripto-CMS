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