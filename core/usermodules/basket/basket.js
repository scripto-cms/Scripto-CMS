$(document).ready(function(){
	$('#choosebyurl').click(function() {
	  var url=prompt('������� url �������, � �������� ����������� �����������:');
	  if (url!='') {
	  	location.href='/admin?module=modules&modAction=settings&module_name=comments&viewbyurl=yes&url=' + url;
	  }
	});
});
