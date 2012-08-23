<h2>Уважаемый администратор сайта {$siteurl}!</h2>
<p>В Вашем магазине был оформлен заказ <b>#{$order_id}</b></p>
<h2>Информация о заказе</h2>
<p>Дата заказа: <b>{$order.date_print}</b></p>
<p>Общая стоимость заказа: <b>{if $order.print_coupon_itogo}{$order.print_coupon_itogo}{else}{$order.print_price}{/if}</b></p>
<p>Способ доставки: <b>{if $order.delivery}{$order.delivery}{else}не указано{/if}</b></p>
<p>Адрес доставки: <b>{if $order.address}{$order.address}{else}не указано{/if}</b></p>
<p>Способ оплаты: <b>{if $order.payment}{$order.payment}{else}не указано{/if}</b></p>
{if $order.comment}
<p>Комментарий к заказу:<br>{$order.comment}</p>
{/if}
<h1>Информация о заказчике</h1>
<p>ФИО: <b>{$user.fio}</b></p>
<p>E-mail: <a href="mailto:{$user.email}">{$user.email}</a></p>
<p>Городской телефон: {if $user.phone1}<b>{$user.phone1}</b>{else}не указан{/if}</p>
<p>Сотовый телефон: {if $user.phone2}<b>{$user.phone2}</b>{else}не указан{/if}</p>
<h1>Заказанные товары</h1>
<table class="objects">
<tr height="30">
	<td width="25%" class="objects_header">Код товара</td>
	<td width="25%" class="objects_header">Название</td>
	<td width="15%" class="objects_header">Количество</td>
	<td width="15%" class="objects_header">Стоимость</td>
	<td width="20%" class="objects_header">Итого</td>
</tr>
{foreach key=key item=prod from=$order.products}
<tr class="{cycle values="objects_cell_light,objects_cell_bold"}" id="order{$ord.id_order}">
	<td>{$prod.code}</td>
	<td>{$prod.caption}
	{if $prod.options}
	<p><b>Заказанные варианты товара:</b></p>
	{foreach key=key item=option from=$prod.options}
	{assign var="n" value=0}
	<p>
		{foreach key=key2 item=value from=$option}
			{if $n==0}
				<b>{$value}</b>:<br>
			{/if}
			{$key2}<br>
			{assign var="n" value=$n+1}
		{/foreach}
	</p>
	{/foreach}
	{/if}
	</td>
	<td>{$prod.count}</td>
	<td>{$prod.print_price}</td>
	<td align="right">{$prod.print_itogo}</td>
</tr>
{/foreach}
<tr>
	<td colspan="4" align="right"><b>Итого с доставкой:</b></td>
	<td align="right"><b>{$order.print_price}</b></td>
</tr>
{if $order.print_coupon}
<tr>
	<td colspan="4" align="right">Скидочный купон {$order.coupon_caption} ({$order.coupon_code})</td>
	<td align="right">- {$order.coupon_price} {if $order.coupon_type==0}руб.{/if}{if $order.coupon_type==1}%{/if}</td>
</tr>
<tr>
	<td colspan="4" align="right"><b>Итого к оплате:</b></td>
	<td align="right"><b>{$order.print_coupon_itogo}</b></td>
</tr>
{/if}
{if $order.print_discount_price}
<tr height="20">
	<td colspan="4" align="right">{$lang.users.discount_value} {$order.discount_caption}</td>
	<td align="right">- {$order.print_discount_price}</td>
</tr>
<tr height="20">
	<td colspan="4" align="right"><b>Итого к оплате (с доставкой):</b></td>
	<td align="right"><b>{$order.print_discount_itog}</b></td>
</tr>
{/if}
</table>