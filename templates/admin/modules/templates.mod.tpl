			<table class="moduletable">
				<tr>
					<td class="module_left">
					<h2>Выберите тип шаблонов</h2>
						<div class="body">
						<ul>
							{if $type=="site"}
							<li>Шаблоны сайта</li>							
							{else}
							<li><a href="{$siteurl}admin/?module=templates&modAction=view&type=site">Шаблоны сайта</a></li>							
							{/if}
							{if $type=="block"}
							<li>Шаблоны блоков</li>							
							{else}
							<li><a href="{$siteurl}admin/?module=templates&modAction=view&type=block">Шаблоны блоков</a></li>
							{/if}
						</ul>
						</div>
						{if $config.template_help.enable}
<h2>Генератор переменных шаблона</h2>
<p>Пожалуйста выберите тип объекта, который Вы хотите вывести:</p>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('blocks');">Функции вывода блоков</span></td></tr>
		<tr class="form_hidden" id="blocks">
			<td class="element">
			<h3>Вывод одного блока</h3>
			 <p>Выберите блок, который нужно вывести:
				<select generate_type="blocks" name="blocks" class="show_hidden" ident="code">
					<option value="">---выберите--</option>
					{foreach key=key item=bl from=$blocks}
					<option value="{$bl.ident}">{$bl.caption}</option>
					{/foreach}
				</select>
			 </p>
			 <h3>Вывод нескольких блоков</h3>
			  <p>
			  	<select generate_type="allblocks" name="allblocks" class="show_hidden" ident="code">
			  		<option value="">---выберите--</option>
					<option value="all">Все блоки</option>					
				</select>
			  </p>
			</td>
		</tr>
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('modules');">Модули</span></td></tr>
		<tr class="form_hidden" id="modules">
			<td class="element">
			<h3>Модули</h3>
			  <p>
			  	<select generate_type="allmodules" name="allmodules" class="show_hidden" ident="code">
			  		<option value="">---выберите--</option>
					<option value="all">Все модули</option>					
				</select>
			  </p>			
			</td>
		</tr>		
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('path');">Путь по сайту (хлебные крошки)</span></td></tr>
		<tr class="form_hidden" id="path">
			<td class="element">
			<h3>Путь по сайту</h3>
			  <p>
			  	<select generate_type="path" name="path" class="show_hidden" ident="code">
			  		<option value="">---выберите--</option>
					<option value="all">Вывести путь по сайту</option>					
				</select>
			  </p>			
			</td>
		</tr>
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('rubrics');">Вывод разделов сайта</span></td></tr>
		<tr class="form_hidden" id="rubrics">
			<td class="element">
			<h3>Вывести разделы</h3>
			  <p>
			  	<select generate_type="rubrics" name="rubrics" class="show_hidden" ident="code">
			  		<option value="">---выберите--</option>
					<option value="razd">Вывести разделы N уровня верхнего меню (up)</option>
					<option value="podrazd">Вывести подразделы текущего раздела</option>
					<option value="tree">Вывести дерево разделов (карта сайта)</option>				
				</select>
			  </p>			
			</td>
		</tr>					
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('page');">Текущий раздел</span></td></tr>
		<tr class="form_hidden" id="page">
			<td class="element">
			<h3>Свойства раздела</h3>
			  <p>
			  	<select generate_type="page" name="page" class="show_hidden" ident="code">
			  		<option value="">---выберите--</option>
					<option value="caption">Название</option>
					<option value="content">Содержание</option>	
					<option value="subcontent">Краткое содержание</option>	
					<option value="url">Ссылка</option>	
					<option value="id_category">ID раздела</option>			
					<option value="id_sub_category">ID родительского раздела</option>		
					<option value="title">Тэг title</option>
					<option value="meta">Тэг meta</option>
					<option value="keywords">Тэг keywords</option>	
					<option value="rss_link">Внешняя ссылка</option>		
					<option value="main_page">Главная страница? (TRUE\FALSE)</option>		
				</select>
			  </p>			
			</td>
		</tr>	
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('site');">Переменные сайта и настройки</span></td></tr>
		<tr class="form_hidden" id="site">
			<td class="element">
			<h3>Переменные сайта</h3>
			  <p>
			  	<select generate_type="site" name="page" class="show_hidden" ident="code">
			  		<option value="">---выберите--</option>
			  		<option value="css">Стили текущего шаблона</option>	
			  		<option value="siteurl">Адрес сайта</option>
			  		<option value="user_upload">Пользовательские файлы</option>
			  		<option value="config">Конфигурационный массив</option>	
			  		<option value="lang">Языковой массив</option>
				</select>
			  </p>			
			</td>
		</tr>			
	</table>
<div class="hidden_element" ident="code">
<h2>Сгенерированный код</h2>
<textarea id="codegenerated" style="width:100%;height:200px;"></textarea>
</div>						
{/if}
					</td>
					<td class="module_right">
						{include file='admin/module_header.tpl.html'}
					<div class="body">
{if $modAction=="view"}
					{if $type!=''}
					<div class="body">
						<div class="actionbutton" onclick="goTo('{$siteurl}admin?module=templates&modAction=add&type={$type}');">Создать шаблон</div>
					</div>
					{/if}
<div class="body">
{if $templates}
<form action="{$siteurl}admin/?module=templates&modAction=view" method="post">
<table class="objects">
<tr height="30">
	<td width="5%" class="objects_header"></td>
	<td width="30%" class="objects_header editable_header"><span>Название</span></td>
	<td width="60%" class="objects_header">Путь</td>
	<td width="5%" class="objects_header" align="right">Операции</td>
</tr>
{assign var="k" value=0}
{foreach key=key item=tpl from=$templates}
<tr class="{cycle values="objects_cell_light,objects_cell_bold"}" id="cat{$category.id_category}">
	<td>{$k+1}.</td>
	<td class="editable"><span>{$tpl.tpl_caption}</span><input type="text" name="caption[{$tpl.id_tpl}]" value="{$tpl.tpl_caption}" class="nonvisible"></td>
	<td>{$tpl.path}</td>
	<td class="actions">
		<ul>
			<li><a href="{$siteurl}admin/?module=templates&modAction=edit&id_tpl={$tpl.id_tpl}" title="{$lang.interface.edit_template} {$tpl.tpl_caption}"><img src="{$img}admin/icons/code.png" border="0"></a></li>
			<li><a href="javascript:void(0);" onclick="YesNo('{$lang.dialog.delete_template}','{$siteurl}admin/?module=templates&modAction=delete&id_tpl={$tpl.id_tpl}&type={$type}');" title="{$lang.interface.delete_template}"><img src="{$img}admin/icons/delete.gif" border="0"></a></li>
		</ul>	
	</td>
</tr>
{assign var="k" value=$k+1}
{/foreach}
<tr id="save_submit">
	<td colspan="4" align="center" style="padding-top:10px;"><input type="submit" value="Сохранить" class="button"></td>
</tr>
</table>
<input type="hidden" name="type" value="{$type}">
<input type="hidden" name="saveme" value="yes">
</form>
{else}
<p>Ни одного шаблона не создано</p>
{/if}
</div>
{elseif $modAction=="add"}
{if $congratulation}
Шаблон добавлен успешно!
{else}
<p><b>Внимание!</b> Для того , чтобы файл шаблона был создан , необходимо, чтобы стояли права 777 на папки: <i>/templates/themes/default/site/</i> и <i>/templates/themes/default/block/</i></p>
<p>Файлы стилей необходимо создавать самостоятельно.</p>
{$form_html}
{/if}
{elseif $modAction=="edit"}
<h2>Редактирование шаблона {$tpl.tpl_caption}</h2>
{if $file_error}
<b>Файла шаблона не существует!</b>
{else}
<form action="" method="post">
<div style="border: 1px solid black; padding: 0px;">
<textarea id="code" cols="120" rows="30" name="code">
{$tpl_html}
</textarea>
</div>
<div style="width:100%;text-align:center;padding-top:10px;">
{if $is_writable}
<input type="hidden" name="save" value="yes">
<input type="submit" class="button" value="Сохранить изменения в шаблоне">
{else}
<b>Внимание!</b><br>
Сохранение шаблона невозможно , т.к. файл {$tpl_path} не доступен для записи.
{/if}
</form>
</div>
{literal}
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea('code', {
    height: "800px",
    parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "parsehtmlmixed.js"],
    stylesheet: ["{/literal}{$js}{literal}codemirror/css/xmlcolors.css", "{/literal}{$js}{literal}codemirror/css/jscolors.css", "{/literal}{$js}{literal}codemirror/css/csscolors.css"],
    path: "{/literal}{$js}{literal}codemirror/js/",
    continuousScanning: 500,
    lineNumbers: true,
    textWrapping: false
  });
</script>
{/literal}
{/if}
{if $have_css}
<h2>Редактирование файла стилей шаблона {$tpl.tpl_caption}</h2>
<form action="" method="post">
<div style="border: 1px solid black; padding: 0px;">
<textarea id="css" cols="120" rows="30" name="csscode">
{$css_html}
</textarea>
</div>
{literal}
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea('css', {
    height: "800px",
    parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "parsehtmlmixed.js"],
    stylesheet: ["{/literal}{$js}{literal}codemirror/css/xmlcolors.css", "{/literal}{$js}{literal}codemirror/css/jscolors.css", "{/literal}{$js}{literal}codemirror/css/csscolors.css"],
    path: "{/literal}{$js}{literal}codemirror/js/",
    continuousScanning: 500,
    lineNumbers: true,
    textWrapping: false
  });
</script>
{/literal}
<div style="width:100%;text-align:center;padding-top:10px;">
{if $is_writable_css}
<input type="hidden" name="savecss" value="yes">
<input type="submit" class="button" value="Сохранить изменения в файле стилей">
{else}
<b>Внимание!</b><br>
Сохранение css файла невозможно , т.к. файл {$css_path} не доступен для записи.
{/if}
</form>
</div>
{/if}
{else}
<p>Пожалуйста выберите тип шаблонов в списке слева</p>
{/if}
		</div>
		</td>
	</tr>
</table>