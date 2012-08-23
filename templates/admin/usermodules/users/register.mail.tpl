<h2>Уважаемый {$user.family} {$user.name} {$user.otch}!</h2>
<p>
Вы успешно зарегистрировались на сайте {$settings.caption} (<a href="{$siteurl}">{$siteurl}</a>) со следующими данными:
<table width="400">
	<tr>
		<td width="40%">Логин</td>
		<td width="60%">{$user.login}</td>
	</tr>
	<tr>
		<td width="40%">Пароль</td>
		<td width="60%">{$password}</td>
	</tr>	
	<tr>
		<td width="40%">ФИО</td>
		<td width="60%">{$user.family} {$user.name} {$user.otch}</td>
	</tr>	
	<tr>
		<td width="40%">E-mail</td>
		<td width="60%">{$user.email}</td>
	</tr>	
	<tr>
		<td width="40%">Город</td>
		<td width="60%">{if $user.city}{$user.city}{else}Не указано{/if}</td>
	</tr>
	<tr>
		<td width="40%">Городской телефон</td>
		<td width="60%">{if $user.phone1}{$user.phone1}{else}Не указано{/if}</td>
	</tr>
	<tr>
		<td width="40%">Сотовый телефон</td>
		<td width="60%">{if $user.phone2}{$user.phone2}{else}Не указано{/if}</td>
	</tr>		
</table>
</p>