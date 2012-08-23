	$(document).ready(function(){
		var searchclick=false;
				
	   $("#sbox").click(function() {
	   	if (searchclick==false) {
	   		$(this).val('');
	   		searchclick=true;
	   	}
	   });
			
			$('.pic a').lightbox();
			$.Lightbox.construct({
				show_linkback:	false,
				opacity: "0.45",
				text: {
			image:		'Изображение',
			of:			'из',
			close:		'Закрыть',
			closeInfo:	'',				
					help: {
						close:		'Нажмите, чтобы закрыть окно',
						interact:	''
					}
				}
				
			});   
			
	 $("#vote_list").change(function() {  
	   	 if ($("#vote_list option:selected").val()!='none') {
   		     $("#vote_list option").each(function () {
                if ($(this).val()=='none') {
                	$(this).remove();
                }
        	 });	   
		 }
	 });
	 
	 $("#vote_btn").click(function() {  
	 	if ($("#vote_list option:selected").val()!='none') {
			var vote_url=$(this).attr("url") + '&vote=' + $("#vote_list option:selected").val();
			$("#vote_div").load(vote_url,function(response) {
				if (response!='ERROR') {
					$("#vote_div").html(response);
				} else {
					alert('В процессе голосования произошла ошибка.');
				}
			});
		} else {
			alert('Вы не выбрали оценку!');
		}
	 });
	 
});
