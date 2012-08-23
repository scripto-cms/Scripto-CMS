<table width="100%">
<tr>
{if $type.medium_preview}
	<td width="40%" valign="top">{if $object.middle_preview}<img src="{$user_thumbnails}{$object.middle_preview}" alt="{$object.caption}" width="200">{/if}</td>
{/if}
	<td valign="top">
		{if $type.use_code}
		<p>{$lang.objects.code} {$type.fulllink_text} <b>{$object.code}</b></p>
		{/if}
		{foreach key=key item=text from=$object.values.texts}
		{if $text.type=="pricerub"}
			{if $text.value}<p>{$text.caption}: <b>{$text.value} руб.</b></p>{/if}
		{elseif $text.type=="priceusd"}
			{if $text.value}<p>{$text.caption}: <b>{$text.value}$</b> </p>{/if}
		{elseif $text.type=="link"}
			{if $text.value!='' && $text.caption!=''}<p>{$text.caption} : <a href="{$text.value}" target="_blank">{$text.value}</a></p>{/if}
		{elseif $text.type=="email"}
			{if $text.value!='' && $text.caption!=''}<p>{$text.caption} : <a href="mailto:{$text.value}">{$text.value}</a></p>{/if}
		{else}
			{if $text.value!=''}<p>{$text.caption} : {$text.value}</p>{/if}
		{/if}
		{/foreach}		
		{foreach key=key item=text from=$object.values.checkbox}
			{if $text.caption}<p>{$text.caption} : {if $text.value}{$lang.objects.yes}{else}{$lang.objects.no}{/if}{/if}</p>
		{/foreach}
		{foreach key=key item=text from=$object.values.lists}
			{if $text.caption}<p>{$text.caption} : {if $text.value}{$text.value}{else}{$lang.objects.null}{/if}{/if}</p>
		{/foreach}				
		{if $type.voters}
			<p>{$lang.objects.vote} {if $type.fulllink_text}{$type.fulllink_text}{else}{$lang.objects.peoples}{/if}: {if $object.voters_count>0}{$object.vote} , {$lang.objects.voted} {$object.voters_count}.{else}{$lang.objects.notvoted}{/if}{if !$object.may_voters} ({$lang.objects.youvoted}){/if}</p>
		{/if}
		{if $object.tags}
		<p>{$lang.objects.tags}: {$object.tags}</p>
		{/if}
	</td>
</tr>
</table>
{if $type.voters && $object.may_voters}
<h2>{$lang.objects.voteto} {if $type.fulllink_text}{$type.fulllink_text}{else}{$lang.objects.peoples}{/if}!</h2>
<div class="body" id="vote_div">
<select name="vote" id="vote_list">
<option value="none">{$lang.objects.choosevote}</option>
<option value="1">{$lang.objects.verybad}</option>
<option value="2">{$lang.objects.bad}</option>
<option value="3">{$lang.objects.normal}</option>
<option value="4">{$lang.objects.good}</option>
<option value="5">{$lang.objects.verygood}</option>
</select>
&nbsp;&nbsp;<input type="button" id="vote_btn" value="{$lang.objects.vote_button}" url="{$page.url}?id_object={$object.id_object}&voted=yes">
</div>
{/if}
{if $object.content_full}
<h2>{$lang.objects.description}</h2>
<div class="body">{$object.content_full}</div>
{/if}
{if $type.use_gallery && $object.images}
<h2>{$lang.objects.photogallery}</h2>
<div class="body">
{foreach key=key item=img from=$object.images}
<div class="pic"><a href="{$user_thumbnails}{$img.medium_photo}" title="{$img.caption}"><img src="{$user_thumbnails}{$img.small_photo}" alt="{$img.caption}"></a></div>
{/foreach}
</div>
{/if}
{if $type.use_videogallery && $object.videos}
<h2>{$lang.objects.videogallery}</h2>
<div class="body">
	<div id="videocontainer" class="videocontainer"></div>
	<script type="text/javascript" src="{$js}player/swfobject.js"></script>
	{literal}
	<script>
	function putVideo(flv,jpg) {
		var s1 = new SWFObject("{/literal}{$js}{literal}player/player.swf","ply","99%","300","9","#000000");
		s1.addParam("allowfullscreen","true");
		s1.addParam("allowscriptaccess","always");
		s1.addParam("flashvars",'file=' + flv + '&image=' + jpg);
		s1.write("videocontainer");
	}
	</script>
	{/literal}
	<div class="list">
	{assign var="k" value=0}
	{foreach key=key item=vid from=$object.videos}
		{if $k==0}
	<script>putVideo('{$user_video}{$vid.filename}','{$user_thumbnails}{$vid.big_preview}');</script>
		{/if}
		{if $vid.preview}
			<div class="box"><a href="javascript:void(0);" onclick="putVideo('{$user_video}{$vid.filename}','{$user_thumbnails}{$vid.big_preview}');" title="{$vid.caption}"><img src="{$user_thumbnails}{$vid.preview}" alt="{$vid.caption}"></a></div>
		{else}
			<div class="box"><a href="javascript:void(0);" onclick="putVideo('{$user_video}{$vid.filename}','{$user_thumbnails}{$vid.big_preview}');">{$vid.caption}</a></div>
		{/if}
		{assign var="k" value=$k+1}
	{/foreach}
	</div>
</div>
{/if}
{if $type.use_objects && $object.objects}
	{foreach key=key item=tp from=$object.objects}
		<h2>{$object_types[$key].caption}</h2>
		{foreach key=key item=obj from=$tp}
			{assign var="type" value=$object_types[$obj.id_type]}
			{include file=$obj.processor}
		{/foreach}
	{/foreach}
{/if}
{if $type.use_files && $object.files}
	<h2>{$lang.objects.files}</h2>
	{if $type.download_only_for_users && $users_install}
	{if $authorized}
		{foreach key=key item=file from=$object.files}
			<p><a href="{$page.url}?id_file={$file.id_file}" target="_blank">{$file.filename}</a> ({$file.size})</p>
		{/foreach}
	{else}
		<p>{$lang.objects.download_registered}</p>
		{foreach key=key item=file from=$object.files}
			<p>{$file.filename} ({$file.size})</p>
		{/foreach}
	{/if}
	{else}
		{foreach key=key item=file from=$object.files}
			<p><a href="{$page.url}?id_file={$file.id_file}" target="_blank">{$file.filename}</a> ({$file.size})</p>
		{/foreach}
	{/if}
{/if}