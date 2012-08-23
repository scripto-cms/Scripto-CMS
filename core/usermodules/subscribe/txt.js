/*Файл для обработки функций csv импорта в модуль каталог товаров*/
$(document).ready(function(){
var button = $('#txt_button'), interval;
new AjaxUpload(button,{
		action: '/admin/?module=modules&module_name=subscribe&m_action=importtxt&modAction=settings', 
		name: 'txt_file',
		onSubmit : function(file, ext){
			// change button text, when user selects file			
			button.text('Загрузка');
			
			// If you want to allow uploading only 1 file at time,
			// you can disable upload button
			this.disable();
			
			// Uploading -> Uploading. -> Uploading...
			interval = window.setInterval(function(){
				var text = button.text();
				if (text.length < 13){
					button.text(text + '.');					
				} else {
					button.text('Загрузка');				
				}
			}, 200);
		},
		onComplete: function(file, response){
			button.text('Импорт из текстового файла');
						
			window.clearInterval(interval);
						
			// enable upload button
			this.enable();
			// add file to the list
			switch (response) {
				case 'wrong_format':
					tooltip("Ошибка при загрузке файла","Поддерживается только загрузка в формате TXT",3000);
				break;
				case 'ok':
					tooltip("","Импорт e-mail успешно завершен!",3000);
				break;
				default:
					tooltip("Ошибка","В процессе импорта произошла ошибка",3000);
			}		
		}
	});

}
);	