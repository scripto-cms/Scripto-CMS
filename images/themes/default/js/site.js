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
			image:		'�����������',
			of:			'��',
			close:		'�������',
			closeInfo:	'',				
					help: {
						close:		'�������, ����� ������� ����',
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
					alert('� �������� ����������� ��������� ������.');
				}
			});
		} else {
			alert('�� �� ������� ������!');
		}
	 });
	 
});
