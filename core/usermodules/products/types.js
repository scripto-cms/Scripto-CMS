/*���� ��� ��������� ������� ���������� ����� � ������ ������� �������*/
$(document).ready(function(){

	$('#createType').click(function() {	
		if ($("#typeName").val()!='') {
			setLoadProcess('���������� ������ ���� ������');
			showLoad('������');
		var ser=$("#typeName").serialize();	$.post('/admin/?module=modules&module_name=products&m_action=add_type&modAction=settings&' + ser,function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "� �������� �������� ��������� ������: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			 } else {
				hideLoad();
			 	switch (response) {
					case 'ERROR':
						tooltip('������','������ ���������� ������ ���� �������',5000);
					break;
					default:
					try {
						var object = jQuery.parseJSON(response);
					} catch(e) {};
					if (object==null) {
						tooltip("������","��� ���������� ���� ������ ��������� �������������� ������, ���������� ��������� ������� ���������� ��� ���",5000);
					} else {
			var clss=$(".objects tr:last").attr('class');
			if (clss=='objects_cell_bold') {
				clss='objects_cell_light';
			} else if (clss=='objects_cell_light') {
				clss='objects_cell_bold';
			} else {
				clss='objects_cell_light';
			}					
					var s_object='<tr class="' + clss + '" id="type' + object.id_type + '"><td class="editable"><span>' + object.caption + '</span><input type="text" name="caption[' + object.id_type + ']" value="' + object.caption + '" class="nonvisible"></td><td class="editable" align="center"><input type="checkbox" name="del[' + object.id_type + ']" class="deletecheckbox" numb="0"></td><td class="actions" align="right"><ul><li><a href="/admin/?module=modules&module_name=products&m_action=edit_type&modAction=settings&id_type=' + object.id_type + '&mode=edit" title="������������� ��� ' + object.caption + '"><img src="/images/admin/icons/edit.gif"></a></li><li><a href="/admin/?module=modules&module_name=products&m_action=options&modAction=settings&ajax=yes&id_type=' + object.id_type + '" class="dialog" title="������ ���� ��� ���� ' + object.caption + '"><img src="/images/admin/icons/cube_blue.gif" alt="������ ���� ��� ���� ' + object.caption + '"></a></li></ul></td></tr>';
						tooltip("��� ������� ��������","��� ������� �������� �������",3000);
						$('.objects').append(s_object);
						$('.objects').removeClass('nonvisible');
						$('.objects2').removeClass('nonvisible');
						$("#typeName").val('');
						$("#clear").empty();
						$("#clear").detach();
					}
				}
			 }
			 });	
		} else {
			tooltip('������','���������� ������� �������� ���� ������!',5000);
		}
	});		

});	