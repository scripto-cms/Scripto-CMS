{if $preview_full}
<div id="videocontainer"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div>
<script type="text/javascript" src="{$js}player/swfobject.js"></script>
<div class="preview_full">
<center>
	<script type="text/javascript">
		var s1 = new SWFObject("{$user_flash}{$object.filename}","ply","99%","400","9","#FFFFFF");
		s1.addParam("allowfullscreen","true");
		s1.addParam("allowscriptaccess","always");
		s1.write("videocontainer");
	</script>
</center>
{if $object.external_url}
<p>{$lang.gallery.source}: <a href="{$object.external_url}" target="_blank">{$object.external_url}</a></p>
{/if}
{if $object.description}
<p><b>{$lang.gallery.description}:</b></p>
<p>{$object.description}</p>
{/if}
</div>
{if $comments_install}
{include file="admin/usermodules/comments/user_module.tpl.html"}
{/if}
{else}
{if $flashes}
{if !$page.main_page}
<p style="padding:10px;">{$lang.gallery.choosepage}: {foreach key=key item=p from=$pages}{if $p==$pg}<b>{$p+1}</b>{else}<a href="{$page.url}?pg={$p}">{$p+1}</a>{/if}&nbsp;{/foreach}</p>
{/if}
{foreach key=key item=image from=$flashes}
<div class="image_container">
 <div class="image">
{if $image.preview}
<a href="{$image.rubric.url}?flash_id={$image.id_flash}"><img src="{$user_thumbnails}{$image.preview}" alt="{$image.caption}" border="0"></a>
{else}
<a href="{$image.rubric.url}?flash_id={$image.id_flash}"><img src="{$siteurl}images/admin/icons/flash.png" alt="{$image.caption}" border="0"></a>
{/if}
 </div>
 <div class="image_caption">
 {$image.caption}
 </div>
</div>
{/foreach}
{/if}
{/if}