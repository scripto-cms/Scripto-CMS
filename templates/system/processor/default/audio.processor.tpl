{if $audio}
<p>{$lang.gallery.listen} {$audio.caption}:</p>
<p><object id="audioplayer{$key}" type="application/x-shockwave-flash" data="{$js}uppod/uppod.swf" width="400" height="35"><param name="bgcolor" value="#ffffff" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="movie" value="{$js}uppod/uppod.swf" /><param name="flashvars" value="uid=audioplayer{$key}&amp;m=audio&amp;file={$user_music}{$audio.filename}" /></object></p>
<p>{$lang.gallery.genre}: <b>{if $audio.genre}{$audio.genre}{else}{$lang.gallery.notset}{/if}</b></p>
<p>{$lang.gallery.length}: <b>{if $audio.prodolzhitelnost}{$audio.prodolzhitelnost}{else}{$lang.gallery.notset}{/if}</b></p>
<p>{$lang.gallery.label}: <b>{if $audio.label}{$audio.label}{else}{$lang.gallery.notset}{/if}</b></p>
{if $audio.external_url}
<p><a href="{$audio.external_url}" target="_blank">{$audio.external_url}</a></p>
{/if}
{if $audio.description}
<p><b>{$lang.gallery.description}</b></p>
{$audio.description}
{/if}
{if $comments_install}
{include file="admin/usermodules/comments/user_module.tpl.html"}
{/if}
{else}
{if $audios}
<script language="JavaScript" src="{$js}uppod/js/uppod_player.js"></script>
<script language="JavaScript" src="{$js}uppod/js/swfobject.js"></script>

<table width="100%">
<tr>
	<td width="10%"></td>
	<td width="50%"><strong>{$lang.gallery.author}</strong></td>
	<td width="40%"><strong>{$lang.gallery.listen}</strong></td>
</tr>
{foreach key=key item=track from=$audios}
<tr>
	<td>{if $track.preview}<img src="{$user_thumbnails}{$track.preview}" border="0" height="20">{/if}</td>
	<td><a href="{$page.url}?id_audio={$track.id_audio}">{$track.caption}</a></td>
	<td><object id="audioplayer{$key}" type="application/x-shockwave-flash" data="{$js}uppod/uppod.swf" width="400" height="35"><param name="bgcolor" value="#ffffff" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="movie" value="{$js}uppod/uppod.swf" /><param name="flashvars" value="uid=audioplayer{$key}&amp;m=audio&amp;file={$user_music}{$track.filename}" /></object></td>
</tr>
{/foreach}
</table>
{if !$page.main_page}
<p style="padding:10px;">{$lang.gallery.choosepage}: {foreach key=key item=p from=$pages}{if $p==$pg}<b>{$p+1}</b>{else}<a href="{$page.url}{if $p>0}?pg={$p}{/if}">{$p+1}</a>{/if}&nbsp;{/foreach}</p>
{/if}
{/if}
{/if}
