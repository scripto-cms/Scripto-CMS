/*���� ��� ��������� ������� csv ������� � ������ ������� �������*/
$(document).ready(function(){
	var csv_positions=0;
	var str='';
	var button = $('#csv_start'), interval;
	var id_object=$('#csv_start').attr("idobject");
new AjaxUpload(button,{
		action: '/admin/?module=modules&module_name=products&m_action=upload_csv&modAction=settings', 
		name: 'csv_file',
		onSubmit : function(file, ext){
			// change button text, when user selects file			
			button.text('��������');
			
			// If you want to allow uploading only 1 file at time,
			// you can disable upload button
			this.disable();
			
			// Uploading -> Uploading. -> Uploading...
			interval = window.setInterval(function(){
				var text = button.text();
				if (text.length < 13){
					button.text(text + '.');					
				} else {
					button.text('��������');				
				}
			}, 200);
		},
		onComplete: function(file, response){
			button.text('������� ���� � ������ ������');
						
			window.clearInterval(interval);
						
			// enable upload button
			this.enable();
			
			// add file to the list
			switch (response) {
				case 'wrong_format':
					tooltip("������ ��� �������� �����","�������������� ������ �������� ����� ������ � ������� CSV",3000);
				break;
				case 'not_copy':
					tooltip("������ ��� �������� �����","��������� ������ ��� ����������� ����� � ����� upload. ���������� ��������� ����� � ������������� ������ �����",3000);
				break;
				case 'ok':
					showLoad('������ ������');
					setLoadProcess('���� ��������, ���������� ��������� �����');
					str=generateStr();
					getCountProducts();
				break;		
				default:
					tooltip("��������� ������!","�������� ������ ������������ ����� " + file + " , ������ ��� ����������� ���������� �� �������, ���� ��� �������� ����� ��������� ������.",0);			
			}		
		}
	});
	
	function generateStr() {
		//��������� ������
		var id_category=$("#csv_cat").val();
		var str='id_cat=' + id_category;
		var sheets=new Array();
		var csv=new Array();
		var all=$('.sheet').length;
		for (i=0;i<all;i++) {
			str=str + '&sheet[' + i + ']=' + $('.sheet[attr=' + i + ']').val() + '&csv[' + i + ']=' + $('.csv[attr=' + i + ']').val();
		}
		return str;
	}
	
	function getCountProducts() {
$.post('/admin/?module=modules&module_name=products&m_action=read_csv&modAction=settings&' + str, function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "� �������� �������� ��������� ������: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			 } else {
			 	switch(response) {
					case 'not_file':
						tooltip("������ �����","���� products.csv �� ������ � ����� upload",3000);
						hideLoad();
					break;
					case "ok":
						tooltip("������ ��������","������ ������ ������� ��������",3000);	
						hideLoad();								
					break;
					case "error":
					default:
						tooltip("������ �����","��� ��������� ������ ��������� ������",3000);							
						hideLoad();
				}
			 }
			 		 
			});			
	}
}
);	