/*Файл для обработки функций csv импорта в модуль каталог товаров*/
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
			button.text('Выбрать файл и начать импорт');
						
			window.clearInterval(interval);
						
			// enable upload button
			this.enable();
			
			// add file to the list
			switch (response) {
				case 'wrong_format':
					tooltip("Ошибка при загрузке файла","Поддерживается только загрузка прайс листов в формате CSV",3000);
				break;
				case 'not_copy':
					tooltip("Ошибка при загрузке файла","Произошла ошибка при копировании файла в папку upload. Пожалуйста проверьте права и существование данной папки",3000);
				break;
				case 'ok':
					showLoad('импорт данных');
					setLoadProcess('Файл загружен, начинается обработка файла');
					str=generateStr();
					getCountProducts();
				break;		
				default:
					tooltip("Произошла ошибка!","Возможно размер загружаемого файла " + file + " , больше чем максимально допустимый на сервере, либо при передаче файла произошла ошибка.",0);			
			}		
		}
	});
	
	function generateStr() {
		//формируем запрос
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
			    var msg = "В процессе загрузки произошла ошибка: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			 } else {
			 	switch(response) {
					case 'not_file':
						tooltip("Ошибка файла","Файл products.csv не найден в папке upload",3000);
						hideLoad();
					break;
					case "ok":
						tooltip("Импорт завершен","Импорт данных успешно завершен",3000);	
						hideLoad();								
					break;
					case "error":
					default:
						tooltip("Ошибка файла","При обработке данных произошла ошибка",3000);							
						hideLoad();
				}
			 }
			 		 
			});			
	}
}
);	