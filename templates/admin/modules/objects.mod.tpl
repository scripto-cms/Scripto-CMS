{if $modAction=='viewvideo'}
{if $video}
<div id="videocontainer"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div>
<script type="text/javascript">
	var s1 = new SWFObject("{$js}player/player.swf","ply","99%","300","9","#FFFFFF");
	s1.addParam("allowfullscreen","true");
	s1.addParam("allowscriptaccess","always");
	s1.addParam("flashvars","file={$user_video}{$video.filename}");
	s1.write("videocontainer");
</script>
{else}
<p>������ � �������, ���� ������������ �����</p>
{/if}
{elseif $modAction=="changepreview"}
{if !$get_rubrics}
	{include file="admin/modules/gallery_previewlist.tpl.html"}
{else}
{if $multiple=="no"}
<input type="hidden" id="multiple" value="{$multiple}">
{/if}
<table class="moduletable">
	<tr>
		<td colspan="2">
			{include file='admin/module_header.tpl.html'}		
		</td>
	</tr>
	<tr>
	<td>
	<table class="moduletable">
	<tr>
		<td class="module_left">
			<div class="image_list">
			{include file="admin/modules/gallery_menu.tpl.html"}
			</div>
		</td>
		<td class="module_right">
			<div class="image_list" id="changepreviewlist">
					{include file="admin/modules/gallery_previewlist.tpl.html"}
			</div>
		</td>
	</tr>
	</table>
	</td>
	</tr>
</table>
<div class="image_buttons">
	{if $type=="video"}
	<table width="100%">
		<tr>
			<td width="50%" align="left"><input type="button" value="������� ������� �����" id="deleteImage"></td>
			<td width="50%" align="right"><input type="button" value="������� �����" id="selectImage" class="bigbutton"></td>
		</tr>
	</table>	
	{else}
	<table width="100%">
		<tr>
			<td width="50%" align="left"><input type="button" value="������� ������� �����������" id="deleteImage"></td>
			<td width="50%" align="right"><input type="button" value="������� �����������" id="selectImage" class="bigbutton"></td>
		</tr>
	</table>
	{/if}
	<input type="hidden" id="type" value="{$type}">
	<input type="hidden" id="ref" value="{$ref}">
	<input type="hidden" id="mode" value="{$mode}">	
</div>
{/if}
{elseif $modAction=="crop"}
{include file='admin/module_header.tpl.html'}
<div class="body">
{if $image}
{if $cropSave}
<script>
parent.tooltip("������ �������","������ �������� �������!",7000);
parent.show_close_dialog=false;
parent.$.fancybox.close();
</script>
{/if}
<input type="hidden" id="medium_x" value="{if $medium_info.0}{$medium_info.0}{else}0{/if}">
<input type="hidden" id="medium_y" value="{if $medium_info.1}{$medium_info.1}{else}0{/if}">
<input type="hidden" id="medium_x2" value="{if $medium_info.2}{$medium_info.2}{else}{if $settings.medium_x==0}{$settings.medium_y}{else}{$settings.medium_x}{/if}{/if}">
<input type="hidden" id="medium_y2" value="{if $medium_info.3}{$medium_info.3}{else}{if $settings.medium_y==0}{$settings.medium_x}{else}{$settings.medium_y}{/if}{/if}">
<input type="hidden" id="medium_w" value="{if $medium_info.4}{$medium_info.4}{else}{$width}{/if}">
<input type="hidden" id="medium_h" value="{if $medium_info.5}{$medium_info.5}{else}{$height}{/if}">
<input type="hidden" id="medium_width" value="{if $medium_info.6}{$medium_info.6}{else}{if $settings.medium_x==0}{$settings.medium_y}{else}{$settings.medium_x}{/if}{/if}">
<input type="hidden" id="medium_height" value="{if $medium_info.7}{$medium_info.7}{else}{if $settings.medium_y==0}{$settings.medium_x}{else}{$settings.medium_y}{/if}{/if}">
<input type="hidden" id="small_x" value="{if $small_info.0}{$small_info.0}{else}0{/if}">
<input type="hidden" id="small_y" value="{if $small_info.1}{$small_info.1}{else}0{/if}">
<input type="hidden" id="small_x2" value="{if $small_info.2}{$small_info.2}{else}{$width}{/if}">
<input type="hidden" id="small_y2" value="{if $small_info.3}{$small_info.3}{else}{$height}{/if}">
<input type="hidden" id="small_w" value="{if $small_info.4}{$small_info.4}{else}{$width}{/if}">
<input type="hidden" id="small_h" value="{if $small_info.5}{$small_info.5}{else}{$height}{/if}">
<input type="hidden" id="small_width" value="{if $small_info.6}{$small_info.6}{else}{if $width}{$width}{else}{if $settings.small_x==0}{$settings.small_y}{else}{$settings.small_x}{/if}{/if}{/if}">
<input type="hidden" id="small_height" value="{if $small_info.7}{$small_info.7}{else}{if $height}{$height}{else}{if $settings.small_y==0}{$settings.small_x}{else}{$settings.small_y}{/if}{/if}{/if}">
<form action="{$siteurl}admin/?module=objects&modAction=crop&ajax=true&doCrop=yes" method="post" id="frm">
<input type="hidden" name="id_photo" value="{$image.id_photo}">
<input type="hidden" name="x" id="x" value="0">
<input type="hidden" name="y" id="y" value="0">
<input type="hidden" name="x2" id="x2" value="{$width}">
<input type="hidden" name="y2" id="y2" value="{$height}">
<input type="hidden" name="w" id="w" value="{$width}">
<input type="hidden" name="h" id="h" value="{$height}">
<table width="100%" class="croptable">
	<tr>
		<td width="50%">��������� ���������� ������� ���: &nbsp;
		<select name="previewlist" id="previewlist">
			<option value="medium">������� ������</option>
			<option value="small" selected>����� ������</option>
		</select></td>
		<td align="right"><input type="button" id="previewcreate2" value="������� ������"></td>
	</tr>
</table>
<div id="editablearena">
	<img src="{$user_images}{$image.big_photo}" id="cropbox" onload="cropMe('small');">
</div>
<div class="body">
<table width="100%">
<tr>
	<td width="25%"><div class="body min">������ �����������: {$size.0}x{$size.1}</div></td>
	<td width="50%" align="center">������� �����: <input type="text" id="width" name="width" value="{$width}" size="3">x<input type="text" name="height" id="height" value="{$height}" size="3">&nbsp;<input type="button" value="���������� ���� ������" id="setrandomsize"></td>
	<td width="25%" align="right"><input type="button" id="previewcreate" value="������� ������" class="bigbutton"></td>
</tr>
</table>
</form>
</div>
{else}
<p><b>������ �������� �����������</b></p>
{/if}
</div>
{elseif $modAction=="viewflash"}
{if $flash}
<div id="videocontainer"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div>
<script type="text/javascript">
	var s1 = new SWFObject("{$user_flash}{$flash.filename}","ply","99%","300","9","#FFFFFF");
	s1.addParam("allowfullscreen","true");
	s1.addParam("allowscriptaccess","always");
	s1.write("videocontainer");
</script>
{else}
<p>������ � �������, ���� ������������ flash</p>
{/if}
{elseif $modAction=="viewaudio"}
{if $audio}
<div id="videocontainer" style="height:18px;"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div>
<script type="text/javascript">
	var s1 = new SWFObject("{$js}player/player.swf","ply","99%","18","9","#FFFFFF");
	s1.addParam("allowfullscreen","true");
	s1.addParam("allowscriptaccess","always");
	s1.addParam("flashvars","file={$user_music}{$audio.filename}&fullscreen=false");
	s1.write("videocontainer");
</script>
{else}
<p>������ � �������, ���� ������������ �����</p>
{/if}
{elseif $modAction=="edit_object"}
{include file='admin/module_header.tpl.html'}
<div class="body">
{if $mode=="video" || $mode=="flash" || $mode=="music" || $mode=="photo"}
{if $saved}
<script>
	parent.renameItem('{$uniq}','{$object.caption|truncate:17}');
	parent.tooltip('�������������� �������','������ ������� ���������!',5000);
	parent.show_close_dialog=false;
	parent.$.fancybox.close();
</script>
{else}
<h1>�������������� �������</h1>
{$form_html}
{/if}
{/if}
</div>
{else}
<table class="moduletable">
	<tr>
		<td colspan="2">
			{include file='admin/module_header.tpl.html'}		
		</td>
	</tr>
	<tr>
		<td class="module_left">
			{include file="admin/modules/gallery_menu.tpl.html"}
		</td>
		<td class="module_right">
		<div class="body">
{if $modAction=="change"}
<h1>��������� ������� ��� ��������</h1>
<p>��� <font style="font-size:18px;">{$objects_count}</font> �������� ���� ����������� �������� {if $do_visible}<b>���������</b>{/if} {if $do_main}, <b>���������� �� �������</b>{/if} {if $do_title} , <b>����������� �������� ��� ���� title</b>{/if}</p>
<input onclick="loadContent('{$siteurl}admin/?module=objects&modAction=edit&modType={$modType}&id_cat={$id_cat}&ajax=true&white=true','module','change');" type="button" value="����������� ������������� �������" class="button">
{elseif $modAction=="ftp"}
<h2>�����, ����������� �� ���</h2>
{if !$go}
<p>��������! ����� ��������� ������ �����, �������������� ��������</p>
<p>� ����� ����� <b>upload/new</b> ���� ������� ��������� �����:</p>
{/if}
{if $files}
<ul style="padding-left:20px;">
{foreach key=key item=file from=$files}
<li>{$file} {if !$go}[<a href="{$config.pathes.user_upload_http}{$file}" class="group" rel="group">�����������</a>]{/if}</li>
{/foreach}
</ul>
{else}
<b>����� �� ���������!</b>
{/if}
{if !$go}
<div class="body">
{if $files}
<div class="actionbutton" onclick="YesNo('����������� ����������� ����� � ���?','{$siteurl}admin/?module=objects&modAction=ftp&go=true');">����������� � ���</div>
{else}
<h2>�� ������ ������ � ����� upload/new ��� �� ������ �����</h2>
{/if}
</div>
{/if}
{elseif $modAction=="seenew"}
<h2>�������� ��������������� ��������</h2>
<div class="body">
<p>��� ����, ����� ��������� ����������� ����� � ������� �������� ����� � �������� � ����� ������ �� ����������� � ������ �����.</p>
</div>
<h2>�������� � ���������</h2>
<div class="body">			
<div id="button1" class="actionbutton">��������� ����</div>
<div id="urldownload" class="actionbutton">��������� �� URL</div>
<div class="actionbutton" onclick="YesNo('����������� ����������� ����� �� ���?','{$siteurl}admin/?module=objects&modAction=ftp');">����������� ����� �� ���</div>
<div id="deletebutton" class="actionbutton">������� �����</div>
</div>
<form id="frm">
{if $modType=="" || $modType=="image"}
<h2>����������� [<a href="javascript:void(0);" class="select_all_objects" type="image">�������� ���</a>&nbsp;/&nbsp;<a href="javascript:void(0);" class="deselect_all_objects" type="image">�������� ���</a>]</h2>
<div class="icons" type="image">
{foreach key=key item=object from=$objects.image}
<div class="preview" id="obj{$object.id_object}">
	<div class="preview_picture" style="background:url('{$user_thumbnails}{$object.preview}') center no-repeat;"><img src="{$img}admin/blank.gif" border="0" width="120" height="130" alt="{$object.caption}"></div>
	<div class="preview_caption">{$object.caption|truncate:17:"..."}</div>
	<input type="hidden" name="objects[{$object.id_object}]" value="0" idobject="{$object.id_object}">
</div>
{/foreach}
</div>
{/if}
{if $modType=="" || $modType=="video"}
<h2>����� ����� [<a href="javascript:void(0);" class="select_all_objects" type="video">�������� ���</a>&nbsp;/&nbsp;<a href="javascript:void(0);" class="deselect_all_objects" type="video">�������� ���</a>]</h2>
<div class="icons" type="video">
{foreach key=key item=object from=$objects.video}
<div class="preview" id="obj{$object.id_object}">
	<div class="preview_picture" style="background:url('{$img}admin/icons/video.png') center no-repeat;"><img src="{$img}admin/blank.gif" border="0" width="120" height="130" alt="{$object.caption}"></div>
	<div class="preview_caption">{$object.caption|truncate:15:"..."}</div>
	<input type="hidden" name="objects[{$object.id_object}]" value="0" idobject="{$object.id_object}">
</div>
{/foreach}
</div>
{/if}
{if $modType=="" || $modType=="music"}
<h2>����� ����� [<a href="javascript:void(0);" class="select_all_objects" type="music">�������� ���</a>&nbsp;/&nbsp;<a href="javascript:void(0);" class="deselect_all_objects" type="music">�������� ���</a>]</h2>
<div class="icons" type="music">
{foreach key=key item=object from=$objects.music}
<div class="preview" id="obj{$object.id_object}">
	<div class="preview_picture" style="background:url('{$img}admin/icons/audio.png') center no-repeat;"><img src="{$img}admin/blank.gif" border="0" width="120" height="130" alt="{$object.caption}"></div>
	<div class="preview_caption">{$object.caption|truncate:15:"..."}</div>
	<input type="hidden" name="objects[{$object.id_object}]" value="0" idobject="{$object.id_object}">
</div>
{/foreach}
</div>
{/if}
{if $modType=="" || $modType=="flash"}
<h2>Flash [<a href="javascript:void(0);" class="select_all_objects" type="flash">�������� ���</a>&nbsp;/&nbsp;<a href="javascript:void(0);" class="deselect_all_objects" type="flash">�������� ���</a>]</h2>
<div class="icons" type="flash">
{foreach key=key item=object from=$objects.flash}
<div class="preview" id="obj{$object.id_object}">
	<div class="preview_picture" style="background:url('{$img}admin/icons/flash.png') center no-repeat;"><img src="{$img}admin/blank.gif" border="0" width="120" height="130" alt="{$object.caption}"></div>
	<div class="preview_caption">{$object.caption|truncate:15:"..."}</div>
	<input type="hidden" name="objects[{$object.id_object}]" value="0" idobject="{$object.id_object}">
</div>
{/foreach}
</div>
{/if}
<input type="hidden" name="modType" value="{$modType}">
</form>
</div>
{elseif $modAction=="edit"}
<h2>������� ������� {$this_cat.caption}</h2>
<div class="body">			
<div class="actionbutton" onclick="goTo('{$siteurl}admin/?module=objects');">��������������� �����</div>
<div class="actionbutton" onclick="YesNo('����������� ����������� ����� �� ���?','{$siteurl}admin/?module=objects&modAction=ftp');">����������� ����� �� ���</div>
<div id="deleteitemsbutton" class="actionbutton">������� �����</div>
</div>
{assign var="k" value=0}
{if $modType=="" || $modType=="image"}
<h2>����������� [<a href="javascript:void(0);" class="select_all_objects" type="image">�������� ���</a>&nbsp;/&nbsp;<a href="javascript:void(0);" class="deselect_all_objects" type="image">�������� ���</a>]</h2>
<div class="icons" type="image">
{foreach key=key item=object from=$objects.image}
{if $object.preview}
<div class="preview" id="photos{$object.id_photo}">
<div class="preview_container">
	<div class="preview_bar">
		<ul>
			<li><a href="{$siteurl}admin/?module=objects&modAction=edit_object&mode=photo&id_object={$object.id_photo}&uniq=photos{$object.id_photo}" title="������������� �������� ����������� {$object.caption}"><img src="{$img}admin/edit_image.png" border="0"></a></li>
			<li><a href="{$user_images}{$object.big_photo}" class="group" rel="group" title="����������� ����������� {$object.caption}" class="dialog"><img src="{$img}admin/view.png" border="0"></a></li>	
			<li><a href="{$siteurl}admin/?module=objects&modAction=crop&id_photo={$object.id_photo}&ajax=true" title="�������� ���������, ���� ������� preview {$object.caption}" class="crop"><img src="{$img}admin/crop.png" border="0"></a></li>			
		</ul>
	</div>
	<div class="preview_picture" style="background:url('{$user_thumbnails}{$object.preview}') center no-repeat;"><img src="{$img}admin/blank.gif" border="0" width="120" height="130" alt="{$object.caption}"></div>
	<div class="preview_caption">{$object.caption|truncate:17:"..."}</div>
	<input type="hidden" name="objects[{$object.id_photo}]" value="0" idobject="{$object.id_photo}" objtype="photos">
</div>
</div>
{/if}
{/foreach}
</div>
{/if}
{if $modType=="" || $modType=="video"}
<h2>����� ����� [<a href="javascript:void(0);" class="select_all_objects" type="video">�������� ���</a>&nbsp;/&nbsp;<a href="javascript:void(0);" class="deselect_all_objects" type="video">�������� ���</a>]</h2>
<div class="icons" type="video">
{foreach key=key item=object from=$objects.video}
<div class="preview" id="videos{$object.id_video}">
<div class="preview_container">
	<div class="preview_bar">
		<ul>
			<li><a href="{$siteurl}admin/?module=objects&modAction=edit_object&mode=video&id_object={$object.id_video}&uniq=videos{$object.id_video}" title="������������� �������� ����������� {$object.caption}"><img src="{$img}admin/edit_image.png" border="0"></a></li>
			<li><a href="{$siteurl}admin/?module=objects&modAction=viewvideo&id_video={$object.id_video}&ajax=true" title="����������� ����������� {$object.caption}" class="video"><img src="{$img}admin/view.png" border="0"></a></li>	
		</ul>
	</div>
	<div class="preview_picture" style="background:url('{$img}admin/icons/video.png') center no-repeat;"><img src="{$img}admin/blank.gif" border="0" width="120" height="130" alt="{$object.caption}"></div>
	<div class="preview_caption">{$object.caption|truncate:15:"..."}</div>
	<input type="hidden" name="objects[{$object.id_video}]" value="0" idobject="{$object.id_video}" objtype="videos">
</div>
</div>
{/foreach}
</div>
{/if}
{if $modType=="" || $modType=="music"}
<h2>����� ����� [<a href="javascript:void(0);" class="select_all_objects" type="music">�������� ���</a>&nbsp;/&nbsp;<a href="javascript:void(0);" class="deselect_all_objects" type="music">�������� ���</a>]</h2>
<div class="icons" type="music">
{foreach key=key item=object from=$objects.music}
<div class="preview" id="audio{$object.id_audio}">
<div class="preview_container">
	<div class="preview_bar">
		<ul>
			<li><a href="{$siteurl}admin/?module=objects&modAction=edit_object&mode=music&id_object={$object.id_audio}&uniq=audio{$object.id_audio}" title="������������� �������� ����������� {$object.caption}"><img src="{$img}admin/edit_image.png" border="0"></a></li>
			<li><a href="{$siteurl}admin/?module=objects&modAction=viewaudio&id_audio={$object.id_audio}&ajax=true" title="���������� ����������� {$object.caption}" class="audio"><img src="{$img}admin/view.png" border="0"></a></li>
		</ul>
	</div>
	<div class="preview_picture" style="background:url('{$img}admin/icons/audio.png') center no-repeat;"><img src="{$img}admin/blank.gif" border="0" width="120" height="130" alt="{$object.caption}"></div>
	<div class="preview_caption">{$object.caption|truncate:15:"..."}</div>
	<input type="hidden" name="objects[{$object.id_audio}]" value="0" idobject="{$object.id_audio}" objtype="audio">
</div>
</div>
{/foreach}
</div>
{/if}
{if $modType=="" || $modType=="flash"}
<h2>Flash [<a href="javascript:void(0);" class="select_all_objects" type="flash">�������� ���</a>&nbsp;/&nbsp;<a href="javascript:void(0);" class="deselect_all_objects" type="flash">�������� ���</a>]</h2>
<div class="icons" type="flash">
{foreach key=key item=object from=$objects.flash}
<div class="preview" id="flash{$object.id_flash}">
<div class="preview_container">
	<div class="preview_bar">
		<ul>
			<li><a href="{$siteurl}admin/?module=objects&modAction=edit_object&mode=flash&id_object={$object.id_flash}&uniq=flash{$object.id_flash}" title="������������� �������� ���������� {$object.caption}"><img src="{$img}admin/edit_image.png" border="0"></a></li>
			<li><a href="{$siteurl}admin/?module=objects&modAction=viewflash&id_flash={$object.id_flash}&ajax=true" title="����������� ��������� {$object.caption}" class="video"><img src="{$img}admin/view.png" border="0"></a></li>	
		</ul>
	</div>
	<div class="preview_picture" style="background:url('{$img}admin/icons/flash.png') center no-repeat;"><img src="{$img}admin/blank.gif" border="0" width="120" height="130" alt="{$object.caption}"></div>
	<div class="preview_caption">{$object.caption|truncate:15:"..."}</div>
	<input type="hidden" name="objects[{$object.id_flash}]" value="0" idobject="{$object.id_flash}" objtype="flash">
</div>
</div>
{/foreach}
</div>
{/if}
{/if}
		</div>
		</td>
	</tr>
</table>
{/if}