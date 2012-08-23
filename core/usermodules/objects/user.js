var show_mess=true;

function addPhoto(url,id_image) {
	$(".object_photos").append('<div class="object_load" style="background:url(' + url + ') top center no-repeat;"><div class="object_over"></div><div class="object_close" idimage="' + id_image + '"><img src="/images/admin/close.png"></div></div>');
}

$(document).ready(function(){

	$('.object_close').live('click', function() {
	  if (confirm('Удалить изображение?')) {
	  	var id_object=$(".object_photos").attr("idobject");
		var id_image=$(this).attr("idimage");
		var obj=$(this).parent('.object_load');
		$.ajax({ url: "?delete=yes&id_object=" + id_object + "&id_image=" + id_image, context: document.body, success: function(){
			$(obj).empty();
        	$(obj).remove();
	    }});
	  	
	  }
	});
	$('.object_load').live('mouseover', function() {
	  $(this).find(".object_over").show();
	});
	$('.object_load').live('mouseout', function() {
	  $(this).find(".object_over").hide();
	});

	$(".upload_form2").fancybox({
	'scrolling'		: 'no',
	'titleShow'		: false,
	'onCleanup'		: function() {
		if (show_mess) {
		    alert('Вам необходимо пройти процедуру загрузки изображения до конца.');
			return false;
		}
	}
	});

	var button=$('#picfilename');
	var idcat=$('#picfilename').attr("idcat");
	var idobject=$('#picfilename').attr("idobject");
	var str_cat='';
	var str_object='';
	if (idcat>0) {
		str_cat='&id_cat=' + idcat;
	}
	if (idobject>0) {
		str_object='&id_object=' + idobject;
	}		

	new AjaxUpload(button,{
		action: 'index.php?user_module=photoupload' + str_cat + str_object, 
		name: 'photoupload',
		onSubmit : function(file, ext){
			$("#picfilename").hide();
			$("#loadme").show();		
		},
		onComplete: function(file, response){
			// add file to the list
			show_mess=false;
			$("#picfilename").show();
			$("#loadme").hide();			
			switch (response) {
				case 'error':
					alert("При загрузке файла произошла ошибка");
					$.fancybox.close();
				break;
				case 'wrong_format':
					alert("Загружаемый формат файла не поддерживается");
					$.fancybox.close();
				break;
				case 'wrong_number':
					alert('Загружено максимально разрешенное количество фото');
					$.fancybox.close();
				break;
				case 'wrong_name':
					alert("Загружаемые файлы должны быть названы латинскими буквами и цифрами, например s8934.jpg");
					$.fancybox.close();
				break
				default:
					try {
					var object = jQuery.parseJSON(response);
					} catch(e) {};
					if (object==null) {
						alert("Возможно размер загружаемого файла " + file + " , больше чем максимально допустимый на сервере, либо при передаче файла произошла ошибка.");
					} else {
						$.fancybox(
						{
							'href'				: 'index.php?user_module=photocrop&id_photo=' + object.id_image,
							'width'				: 880,
							'height'			: 800,
							'titleShow'		: false,
					        'autoScale'     	: false,
					        'scrolling'			: 'no',
					        'transitionIn'		: 'none',
							'transitionOut'		: 'none',
							'type'				: 'iframe',
							'onCleanup'			: function() {
								if (show_mess) {
							    	alert('Вам необходимо пройти процедуру загрузки изображения до конца.');
									return false;
								}
							}
						});
						show_mess=true;
					}	
			}		
		}
	});	

});