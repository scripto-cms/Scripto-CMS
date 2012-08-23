var all_emails=0;
var current_number=0;
var id_archive=null;
var onetime=3;
var percent=0;
var p=0;

function doSubscribe() {
	if (current_number<all_emails) {
		setLoadProcess('Отправка писем ' + Math.round(percent) + '%');
			$.post('/admin/?module=modules&module_name=subscribe&m_action=do_subscribe&modAction=settings&id_archive=' + id_archive + '&p=' + p + '&onpage=' + onetime,function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "В процессе загрузки произошла ошибка: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			  } else {
				if (response=='ok') {
					current_number=current_number+onetime;
					percent=(current_number/all_emails)*100;
					p++;					
					doSubscribe();
				} else {
					hideLoad();
					tooltip('Ошибка','В процессе рассылки произошла ошибка!',5000);
				}
			  }
			});			
	} else {
		hideLoad();
		tooltip('','Рассылка успешно завершена!',5000);
	}
}

$(document).ready(function(){
	$('#add_email_button').click(function() {
	  var email=prompt('Укажите добавляемый e-mail адрес:');
	  if (email!='') {
	  	location.href='/admin?module=modules&modAction=settings&module_name=subscribe&add=yes&email=' + email;
	  }
	});
	
	$('.start_subscribe').click(function() {
		id_archive=$(this).attr("id_archive");
			setLoadProcess('Получение количества участников рассылки');
			showLoad('Рассылка писем');
			current_number=0;
			percent=0;
			p=0;
			$.post('/admin/?module=modules&module_name=subscribe&m_action=get_count&modAction=settings',function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "В процессе загрузки произошла ошибка: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			  } else {
				all_emails=response;
				if (all_emails>0) {
					doSubscribe();
				} else {
					hideLoad();
					tooltip('Ошибка','В Вашей базе нет участников рассылки',5000);
				}
			  }
			});		
	});
});