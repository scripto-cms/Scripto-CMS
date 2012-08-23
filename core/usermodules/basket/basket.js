$(document).ready(function(){
	$('#choosebyurl').click(function() {
	  var url=prompt('Укажите url объекта, к которому просмотреть комментарии:');
	  if (url!='') {
	  	location.href='/admin?module=modules&modAction=settings&module_name=comments&viewbyurl=yes&url=' + url;
	  }
	});
});
