var show_mess=true;

function addPhoto(url,id_image) {
	$(".object_photos").append('<div class="object_load" style="background:url(' + url + ') top center no-repeat;"><div class="object_over"></div><div class="object_close" idimage="' + id_image + '"><img src="/images/admin/close.png"></div></div>');
}

$(document).ready(function(){

	$(".upload_form2").fancybox({
	'scrolling'		: 'no',
	'titleShow'		: false,
	'onCleanup'		: function() {
		if (show_mess) {
		    alert('��� ���������� ������ ��������� �������� ����������� �� �����.');
			return false;
		}
	}
	});

	var button=$('#picfilename');
	var idcat=$('#picfilename').attr("idcat");
	var iduser=$('#picfilename').attr("iduser");
	var width=$('#picfilename').attr("wdth");
	var height=$('#picfilename').attr("hght");	
	var str_cat='';
	var str_object='';
	var str_width='&width=' + width;
	var str_height='&height=' + height;
	if (idcat>0) {
		str_cat='&id_cat=' + idcat;
	}
	if (iduser>0) {
		str_object='&id_user=' + iduser;
	}

	new AjaxUpload(button,{
		action: 'index.php?user_module=avatarupload' + str_cat + str_object + str_width + str_height, 
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
					alert("��� �������� ����� ��������� ������");
					$.fancybox.close();
				break;
				case 'wrong_format':
					alert("����������� ������ ����� �� ��������������");
					$.fancybox.close();
				break;
				case 'wrong_name':
					alert("����������� ����� ������ ���� ������� ���������� ������� � �������, �������� s8934.jpg");
					$.fancybox.close();
				break
				default:
					try {
					var object = jQuery.parseJSON(response);
					} catch(e) {};
					if (object==null) {
						alert("�������� ������ ������������ ����� " + file + " , ������ ��� ����������� ���������� �� �������, ���� ��� �������� ����� ��������� ������.");
					} else {
						$.fancybox(
						{
							'href'				: 'index.php?user_module=photocrop&id_photo=' + object.id_image + str_width + str_height,
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
							    	alert('��� ���������� ������ ��������� �������� ����������� �� �����.');
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