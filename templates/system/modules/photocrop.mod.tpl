<div class="body">
<h1>Пожалуйста выберите фрагмент изображения</h1>
<div class="body">
{if $image}
{if $cropSave}
<script>
parent.addPhoto('{$user_thumbnails}{$image.small_photo}','{$image.id_photo}');
parent.show_mess=false;
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
<form action="/index.php?user_module=photocrop&doCrop=yes" method="post" id="frm">
<input type="hidden" name="id_photo" value="{$image.id_photo}">
<input type="hidden" name="x" id="x" value="0">
<input type="hidden" name="y" id="y" value="0">
<input type="hidden" name="x2" id="x2" value="{$width}">
<input type="hidden" name="y2" id="y2" value="{$height}">
<input type="hidden" name="w" id="w" value="{$width}">
<input type="hidden" name="h" id="h" value="{$height}">
<input type="hidden" name="width" id="width" value="{$width}">
<input type="hidden" name="height" id="height" value="{$height}">
<input type="hidden" name="previewlist" id="previewlist" value="small">
</form>
<div id="editablearena">
	<img src="{$user_images}{$image.big_photo}" id="cropbox" onload="cropMe('small');">
</div>
<div class="body">
<table width="100%">
<tr>
	<td width="50%"><div class="body min">Размер изображения: {$size.0}x{$size.1}</div></td>
	<td width="50%" align="right"><input type="button" id="previewcreate" value="Создать превью"></td>
</tr>
</table>
</div>
{else}
<p><b>Ошибка загрузки изображения</b></p>
{/if}
</div>