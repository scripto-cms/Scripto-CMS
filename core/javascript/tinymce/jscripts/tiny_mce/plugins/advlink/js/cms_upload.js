function chooseImg(btn) {
	new AjaxUpload(btn,{
		action: '/?user_module=tinyupload&mode=file', 
		name: 'image',
		onSubmit : function(file, ext){
			// change button text, when user selects file			
			btn.html('Uploading...');
//			this.disable();
		},
		onComplete: function(file, response){
			btn.html('Upload');
			
			// add file to the list
			switch (response) {
				case 'wrong_format':
					alert('Uploading PHP files not supported');
				break;
				case 'error':
					alert('Uploading error');
				break;	
				default:
					$("#href").val(response);
					mcTabs.displayTab('general_tab','general_panel');
			}		
		}
	});
}


$(document).ready(function(){
	chooseImg($("#upldbtn"));
	$("#upldbtn").live("click", function(){
		chooseImg($(this));
	});
	
});