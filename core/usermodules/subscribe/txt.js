/*���� ��� ��������� ������� csv ������� � ������ ������� �������*/
$(document).ready(function(){
var button = $('#txt_button'), interval;
new AjaxUpload(button,{
		action: '/admin/?module=modules&module_name=subscribe&m_action=importtxt&modAction=settings', 
		name: 'txt_file',
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
			button.text('������ �� ���������� �����');
						
			window.clearInterval(interval);
						
			// enable upload button
			this.enable();
			// add file to the list
			switch (response) {
				case 'wrong_format':
					tooltip("������ ��� �������� �����","�������������� ������ �������� � ������� TXT",3000);
				break;
				case 'ok':
					tooltip("","������ e-mail ������� ��������!",3000);
				break;
				default:
					tooltip("������","� �������� ������� ��������� ������",3000);
			}		
		}
	});

}
);	