{if $preview_full}
{assign var="url" value=$settings.url}
{foreach key=key item=rubr from=$rubrics.right}
{if $rubr.level==0 && $rubr.selected && $rubr.rss_link}
{assign var="url" value=$rubr.rss_link}
{/if}
{/foreach}
{if $parent}
<p style="padding-top:5px;padding-bottom:5px;"><a href="{$parent.url}"><< {$lang.gallery.backto}</a></p>
{/if}
<div class="preview_full">
<center>
{if $config.images.random_mode>=1}
{assign var="k" value=$k|rand:$config.images.random_mode}
{else}
{assign var="k" value=0}
{/if}
{if $k!=1}
<a href="{$page.url}?photo_id={$next_photo}">
{else}
<a href="{$url}" target="_blank">
{/if}
<img src="{$user_thumbnails}{$user_image}{$object.medium_photo}" alt="{$page.caption}" border="0">
</a>
</center>
{if $object.description}
<p><b>{$lang.gallery.description}:</b></p>
<p>{$object.description}</p>
{/if}
</div>
{if $comments_install}
{include file="admin/usermodules/comments/user_module.tpl.html"}
{/if}
<h1>{$lang.gallery.other_photos}:</h1>
{foreach key=key item=img from=$images}
{if $img.id_photo!=$object.id_photo}
<div class="image_container">
 <div class="image">
 {if $config.images.random_mode>=1}
 {assign var="k" value=$k|rand:$config.images.random_mode}
 {else}
 {assign var="k" value=0}
 {/if}
 {if $k==1}
 <a href="{$url}" target="_blank">
 {else}
<a href="{$img.rubric.url}?photo_id={$img.id_photo}">{/if}<img src="{$user_thumbnails}{$user_image}{$img.small_photo}" alt="{$img.caption}" border="0"></a>
 </div>
</div>
{else}
<div class="image_container">
 <div class="image">
 {if $config.images.random_mode>=1}
 {assign var="k" value=$k|rand:$config.images.random_mode}
 {else}
 {assign var="k" value=0}
 {/if}
 {if $k==1}
 <a href="{$url}" target="_blank">
 {else}
<a href="{$img.rubric_url}?photo_id={$img.id_photo}">{/if}<img src="{$user_thumbnails}{$user_image}{$img.small_photo}" alt="{$img.caption}" border="0"></a>
 </div>
</div>
{/if}
{/foreach}
<div class="photos">
{foreach key=key item=img from=$images_full}
{if $img==$object.id_photo}
<b>{$key+1}</b>&nbsp;
{else}
<a href="{$object.rubric_url}?photo_id={$img}">{$key+1}</a>&nbsp;
{/if}
{/foreach}
</div>
{else}
{if $images}
{assign var="url" value=$settings.url}
{foreach key=key item=rubr from=$rubrics.right}
{if $rubr.level==0 && $rubr.selected && $rubr.rss_link}
{assign var="url" value=$rubr.rss_link}
{/if}
{/foreach}
{foreach key=key item=img from=$images}
 <div class="pic">
 <a href="{$user_thumbnails}{$user_image}{$img.medium_photo}" title="{$img.caption}"><img src="{$user_thumbnails}{$user_image}{$img.small_photo}" alt="{$img.caption}" border="0"></a>
 </div>
{/foreach}
{if !$page.main_page}
<div style="width:100%;float:left;">
<p style="padding:20 0px;">{$lang.gallery.choosepage}: {foreach key=key item=p from=$pages}{if $p==$pg}<b>{$p+1}</b>{else}<a href="{$page.url}?pg={$p}">{$p+1}</a>{/if}&nbsp;{/foreach}</p>
</div>
{/if}
{/if}
{/if}