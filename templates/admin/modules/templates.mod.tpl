			<table class="moduletable">
				<tr>
					<td class="module_left">
					<h2>�������� ��� ��������</h2>
						<div class="body">
						<ul>
							{if $type=="site"}
							<li>������� �����</li>							
							{else}
							<li><a href="{$siteurl}admin/?module=templates&modAction=view&type=site">������� �����</a></li>							
							{/if}
							{if $type=="block"}
							<li>������� ������</li>							
							{else}
							<li><a href="{$siteurl}admin/?module=templates&modAction=view&type=block">������� ������</a></li>
							{/if}
						</ul>
						</div>
						{if $config.template_help.enable}
<h2>��������� ���������� �������</h2>
<p>���������� �������� ��� �������, ������� �� ������ �������:</p>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('blocks');">������� ������ ������</span></td></tr>
		<tr class="form_hidden" id="blocks">
			<td class="element">
			<h3>����� ������ �����</h3>
			 <p>�������� ����, ������� ����� �������:
				<select generate_type="blocks" name="blocks" class="show_hidden" ident="code">
					<option value="">---��������--</option>
					{foreach key=key item=bl from=$blocks}
					<option value="{$bl.ident}">{$bl.caption}</option>
					{/foreach}
				</select>
			 </p>
			 <h3>����� ���������� ������</h3>
			  <p>
			  	<select generate_type="allblocks" name="allblocks" class="show_hidden" ident="code">
			  		<option value="">---��������--</option>
					<option value="all">��� �����</option>					
				</select>
			  </p>
			</td>
		</tr>
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('modules');">������</span></td></tr>
		<tr class="form_hidden" id="modules">
			<td class="element">
			<h3>������</h3>
			  <p>
			  	<select generate_type="allmodules" name="allmodules" class="show_hidden" ident="code">
			  		<option value="">---��������--</option>
					<option value="all">��� ������</option>					
				</select>
			  </p>			
			</td>
		</tr>		
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('path');">���� �� ����� (������� ������)</span></td></tr>
		<tr class="form_hidden" id="path">
			<td class="element">
			<h3>���� �� �����</h3>
			  <p>
			  	<select generate_type="path" name="path" class="show_hidden" ident="code">
			  		<option value="">---��������--</option>
					<option value="all">������� ���� �� �����</option>					
				</select>
			  </p>			
			</td>
		</tr>
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('rubrics');">����� �������� �����</span></td></tr>
		<tr class="form_hidden" id="rubrics">
			<td class="element">
			<h3>������� �������</h3>
			  <p>
			  	<select generate_type="rubrics" name="rubrics" class="show_hidden" ident="code">
			  		<option value="">---��������--</option>
					<option value="razd">������� ������� N ������ �������� ���� (up)</option>
					<option value="podrazd">������� ���������� �������� �������</option>
					<option value="tree">������� ������ �������� (����� �����)</option>				
				</select>
			  </p>			
			</td>
		</tr>					
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('page');">������� ������</span></td></tr>
		<tr class="form_hidden" id="page">
			<td class="element">
			<h3>�������� �������</h3>
			  <p>
			  	<select generate_type="page" name="page" class="show_hidden" ident="code">
			  		<option value="">---��������--</option>
					<option value="caption">��������</option>
					<option value="content">����������</option>	
					<option value="subcontent">������� ����������</option>	
					<option value="url">������</option>	
					<option value="id_category">ID �������</option>			
					<option value="id_sub_category">ID ������������� �������</option>		
					<option value="title">��� title</option>
					<option value="meta">��� meta</option>
					<option value="keywords">��� keywords</option>	
					<option value="rss_link">������� ������</option>		
					<option value="main_page">������� ��������? (TRUE\FALSE)</option>		
				</select>
			  </p>			
			</td>
		</tr>	
		<tr><td valign="middle" class="form_separator"><span onclick="javascript:show_hide_form('site');">���������� ����� � ���������</span></td></tr>
		<tr class="form_hidden" id="site">
			<td class="element">
			<h3>���������� �����</h3>
			  <p>
			  	<select generate_type="site" name="page" class="show_hidden" ident="code">
			  		<option value="">---��������--</option>
			  		<option value="css">����� �������� �������</option>	
			  		<option value="siteurl">����� �����</option>
			  		<option value="user_upload">���������������� �����</option>
			  		<option value="config">���������������� ������</option>	
			  		<option value="lang">�������� ������</option>
				</select>
			  </p>			
			</td>
		</tr>			
	</table>
<div class="hidden_element" ident="code">
<h2>��������������� ���</h2>
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
						<div class="actionbutton" onclick="goTo('{$siteurl}admin?module=templates&modAction=add&type={$type}');">������� ������</div>
					</div>
					{/if}
<div class="body">
{if $templates}
<form action="{$siteurl}admin/?module=templates&modAction=view" method="post">
<table class="objects">
<tr height="30">
	<td width="5%" class="objects_header"></td>
	<td width="30%" class="objects_header editable_header"><span>��������</span></td>
	<td width="60%" class="objects_header">����</td>
	<td width="5%" class="objects_header" align="right">��������</td>
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
	<td colspan="4" align="center" style="padding-top:10px;"><input type="submit" value="���������" class="button"></td>
</tr>
</table>
<input type="hidden" name="type" value="{$type}">
<input type="hidden" name="saveme" value="yes">
</form>
{else}
<p>�� ������ ������� �� �������</p>
{/if}
</div>
{elseif $modAction=="add"}
{if $congratulation}
������ �������� �������!
{else}
<p><b>��������!</b> ��� ���� , ����� ���� ������� ��� ������ , ����������, ����� ������ ����� 777 �� �����: <i>/templates/themes/default/site/</i> � <i>/templates/themes/default/block/</i></p>
<p>����� ������ ���������� ��������� ��������������.</p>
{$form_html}
{/if}
{elseif $modAction=="edit"}
<h2>�������������� ������� {$tpl.tpl_caption}</h2>
{if $file_error}
<b>����� ������� �� ����������!</b>
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
<input type="submit" class="button" value="��������� ��������� � �������">
{else}
<b>��������!</b><br>
���������� ������� ���������� , �.�. ���� {$tpl_path} �� �������� ��� ������.
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
<h2>�������������� ����� ������ ������� {$tpl.tpl_caption}</h2>
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
<input type="submit" class="button" value="��������� ��������� � ����� ������">
{else}
<b>��������!</b><br>
���������� css ����� ���������� , �.�. ���� {$css_path} �� �������� ��� ������.
{/if}
</form>
</div>
{/if}
{else}
<p>���������� �������� ��� �������� � ������ �����</p>
{/if}
		</div>
		</td>
	</tr>
</table>