<h2>��������� {$user.family} {$user.name} {$user.otch}!</h2>
<p>
�� ������� ������������������ �� ����� {$settings.caption} (<a href="{$siteurl}">{$siteurl}</a>) �� ���������� �������:
<table width="400">
	<tr>
		<td width="40%">�����</td>
		<td width="60%">{$user.login}</td>
	</tr>
	<tr>
		<td width="40%">������</td>
		<td width="60%">{$password}</td>
	</tr>	
	<tr>
		<td width="40%">���</td>
		<td width="60%">{$user.family} {$user.name} {$user.otch}</td>
	</tr>	
	<tr>
		<td width="40%">E-mail</td>
		<td width="60%">{$user.email}</td>
	</tr>	
	<tr>
		<td width="40%">�����</td>
		<td width="60%">{if $user.city}{$user.city}{else}�� �������{/if}</td>
	</tr>
	<tr>
		<td width="40%">��������� �������</td>
		<td width="60%">{if $user.phone1}{$user.phone1}{else}�� �������{/if}</td>
	</tr>
	<tr>
		<td width="40%">������� �������</td>
		<td width="60%">{if $user.phone2}{$user.phone2}{else}�� �������{/if}</td>
	</tr>		
</table>
</p>