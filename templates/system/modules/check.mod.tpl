		<h2>����� �� ������������� ����������� ����������� PHP � Apache</h2>
		<table width="100%">
		<tr>
			<td width="80%" class="header"><b>����������</b></td>
			<td width="20%" class="header" align="center"><b>������</b></td>
		</tr>
			{foreach key=key item=lib from=$report}
				<tr height="30" class="{cycle values="objects_cell_light,objects_cell_bold"}">
					<td>{$lib.value}</td>
					<td align="center">{if $lib.install}<img src="/images/admin/ok.png" alt="�����������">{else}<img src="/images/admin/error.png" alt="�� �����������">{/if}</td>
				</tr>
			{/foreach}
		</table>
		<h2>�������� ������������� �������� �������</h2>
		<p>� ������ ������� ��������� ������������� ��������� �������, ��������� ������ ������� ����� ���������� �� �������������.</p>
		<table width="100%" class="objects">
		<tr>
			<td width="80%" class="header"><b>��������</b></td>
			<td width="20%" class="header" align="center"><b>������</b></td>
		</tr>
			{foreach key=key item=lb from=$setups}
				<tr height="30" class="{cycle values="objects_cell_light,objects_cell_bold"}">
					<td>{$lb.value}</td>
					<td align="center">{if $lb.install}<img src="/images/admin/ok.png" alt="�����������">{else}<img src="/images/admin/alarm.png" alt="�� �����������">{/if}</td>
				</tr>
			{/foreach}		
		</table>
		<h2>�������� ��������� ����������� ���� �� ����� � �����</h2>
		<p>� ������ ������� �������� ����� � �������� ����������� ������ � ����� <b>�� ������</b>, ���� ��������� ����� ��� ����� ���������� ��� ������, ��� ���������� ������� ���������� �����, ����������� ��� ������ (������ ��� 755,775).</p>
		<table width="100%" class="objects">
		<tr>
			<td width="80%" class="header"><b>����\�����</b></td>
			<td width="20%" class="header" align="center"><b>������</b></td>
		</tr>
			{foreach key=key item=file from=$files}
				<tr height="30" class="{cycle values="objects_cell_light,objects_cell_bold"}">
					<td>{$file.value}</td>
					<td align="center">{if $file.install}<img src="/images/admin/ok.png" alt="�����������">{else}<img src="/images/admin/error.png" alt="�� �����������">{/if}</td>
				</tr>
			{/foreach}		
		</table>