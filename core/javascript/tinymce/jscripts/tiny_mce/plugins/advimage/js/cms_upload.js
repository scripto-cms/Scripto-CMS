function chooseImg(btn) {
	new AjaxUpload(btn,{
		action: '/?user_module=tinyupload', 
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
					alert('Upload support only image files (JPG,GIF,PNG)');
				break;
				case 'error':
					alert('Uploading error');
				break;	
				default:
					$("#src").val(response);
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