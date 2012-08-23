		function setFile(filename,id_file) {
			var clss=$(".pictures tr:last").attr('class');
			if (clss=='objects_cell_bold') {
				clss='objects_cell_light';
			} else if (clss=='objects_cell_light') {
				clss='objects_cell_bold';
			} else {
				clss='objects_cell_bold';
			}
			var img_html='<tr class="' + clss + '" id="file' + id_file + '"><td id="preview' + id_file + '"><a href="/upload/files/' + filename + '" target="_blank">' + filename + '</a></td><td align="center">0</td><td align="center"><input type="text" class="textbox" name="sort[' + id_file + ']" value="0" size="3"></td><td align="center"><input type="checkbox" name="del[' + id_file + ']" class="deletecheckbox" numb="0"></td></tr>';
			$(".toDel").empty();
			$(".toDel").die();	
			$(".pictures").append(img_html);
			$(".pictures").show();
			$(".pictures2").removeClass('nonvisible');
		}
		
$(document).ready(function(){

	var button = $('#filesbutton'), interval;
	var id_object=$('#filesbutton').attr("idobject");
new AjaxUpload(button,{
		action: '/index.php?user_module=objectupload&id_object=' + id_object, 
		name: 'photoupload',
		onSubmit : function(file, ext){
			// change button text, when user selects file			
			button.text('Загрузка');
			
			// If you want to allow uploading only 1 file at time,
			// you can disable upload button
			this.disable();
			
			// Uploding -> Uploading. -> Uploading...
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
			button.text('Загрузить файл');
						
			window.clearInterval(interval);
						
			// enable upload button
			this.enable();
			
			// add file to the list
			switch (response) {
				case 'wrong_id_object':
					tooltip("Ошибка при загрузке файла","Неверно передан идентификатор объекта",3000);
				break;
				case 'wrong_format':
					tooltip("Ошибка при загрузке файла","Данный формат файлов запрещен к загрузке",3000);
				break;
				default:
					try {
					var object = jQuery.parseJSON(response);
					} catch(e) {};
					if (object==null) {
						tooltip("Произошла ошибка!","Возможно размер загружаемого файла " + file + " , больше чем максимально допустимый на сервере, либо при передаче файла произошла ошибка.",0);
					} else {
					tooltip("Файл загружен","Файл " + file + " загружен успешно!",3000);					
					setFile(object.filename,object.id_file);	
					}				
			}		
		}
	});
}
);	