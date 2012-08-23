{if $preview_full}
<div id="videocontainer"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div>
<script type="text/javascript" src="{$js}player/swfobject.js"></script>
<div class="preview_full">
<center>
	<script type="text/javascript">
		var s1 = new SWFObject("{$js}player/player.swf","ply","99%","400","9","#FFFFFF");
		s1.addParam("allowfullscreen","true");
		s1.addParam("allowscriptaccess","always");
		s1.addParam("flashvars","file={$user_video}{$object.filename}&image={$user_thumbnails}{$object.big_preview}");
		s1.write("videocontainer");
	</script>
</center>
<p>{$lang.gallery.video_company}: <b>{if $object.company}{$object.company}{else}{$lang.gallery.notset}{/if}</b></p>
<p>{$lang.gallery.length}: <b>{if $object.prodolzhitelnost}{$object.prodolzhitelnost}{else}{$lang.gallery.notset}{/if}</b></p>
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
<h1>{$lang.gallery.other_videos}:</h1>
{foreach key=key item=vid from=$videos}
{if $vid.id_video!=$object.id_video}
<div class="image_container">
 <div class="image">
{if $vid.preview}
<a href="{$vid.rubric.url}?video_id={$vid.id_video}"><img src="{$user_thumbnails}{$user_image}{$vid.preview}" alt="{$vid.caption}" border="0"></a>
{else}
<a href="{$vid.rubric.url}?video_id={$vid.id_video}"><img src="{$img}gallery/novideo.jpg" alt="{$vid.caption}" border="0"></a>
{/if}
 <div class="image_caption"><a href="{$vid.rubric.url}?video_id={$vid.id_video}">{$vid.caption}</a></div>
 </div>
</div>
{else}
<div class="image_container">
 <div class="image">
{if $vid.preview}
<a href="{$vid.rubric.url}?video_id={$vid.id_video}"><img src="{$user_thumbnails}{$user_image}{$vid.preview}" alt="{$vid.caption}" border="0"></a>
{else}
<a href="{$vid.rubric.url}?video_id={$vid.id_video}"><img src="{$img}gallery/novideo.jpg" alt="{$vid.caption}" border="0"></a>
{/if}
 </div>
 <div class="image_caption"><b>{$vid.caption}</b></div>
</div> 
{/if}
{/foreach}
{else}
{if $videos}
{if !$page.main_page}
<p style="padding:10px;">{$lang.gallery.choosepage}: {foreach key=key item=p from=$pages}{if $p==$pg}<b>{$p+1}</b>{else}<a href="{$page.url}?pg={$p}">{$p+1}</a>{/if}&nbsp;{/foreach}</p>
{/if}
{foreach key=key item=image from=$videos}
<div class="image_container">
 <div class="image" style="height:80px;">
{if $image.preview}
<a href="{$image.rubric.url}?video_id={$image.id_video}"><img src="{$user_thumbnails}{$image.preview}" alt="{$image.caption}" border="0"></a>
{else}
<a href="{$image.rubric.url}?video_id={$image.id_video}"><img src="{$img}gallery/novideo.jpg" alt="{$image.caption}" border="0"></a>
{/if}
 </div>
 <div class="image_caption">
 {$image.caption}
 </div>
</div>
{/foreach}
{/if}
{/if}