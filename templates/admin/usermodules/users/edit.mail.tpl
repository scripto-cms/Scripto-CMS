<p>��� ������� ��� �������������� , ����� ������ �����:</p>
<table width="400">
	<tr>
		<td width="40%">�����</td>
		<td width="60%">{$cuser.login}</td>
	</tr>
	{if $password}
	<tr>
		<td width="40%">������</td>
		<td width="60%">{$password}</td>
	</tr>	
	{/if}
	<tr>
		<td width="40%">���</td>
		<td width="60%">{$cuser.family} {$cuser.name} {$cuser.otch}</td>
	</tr>	
	<tr>
		<td width="40%">E-mail</td>
		<td width="60%">{$cuser.email}</td>
	</tr>	
	<tr>
		<td width="40%">�����</td>
		<td width="60%">{if $cuser.city}{$cuser.city}{else}�� �������{/if}</td>
	</tr>
	<tr>
		<td width="40%">��������� �������</td>
		<td width="60%">{if $cuser.phone1}{$cuser.phone1}{else}�� �������{/if}</td>
	</tr>
	<tr>
		<td width="40%">������� �������</td>
		<td width="60%">{if $cuser.phone2}{$cuser.phone2}{else}�� �������{/if}</td>
	</tr>		
</table>
</p>