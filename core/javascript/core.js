/*
Библиотека javascript функций для Scripto CMS
*/
	var tchk=true;
	var show_close_dialog=true;
	var crop_api=null;
	var ajaxloader='<img src="/images/admin/ajax-loader.gif" border="0">';
	var has_reminders=false;
	var show_zv=false;
	var title=document.title;	
	var mainMode=0;
	
	function selectImage() {
		var all=$('.selected').length;
		if ($("#type").val()=='video') {
			var confirm_text='Сохранить видео?';
		} else if ($("#type").val()=='object') {
			var confirm_text='Выбрать объект?';
		} else {
			var confirm_text='Выбрать превью?';
		}
		if (all>0) {
			if (confirm(confirm_text)) {
				if ($("#type").val()=='video') {
				var url=$("#ref").val() + '&ajax=true&previewMode=' + $("#mode").val() + '&setVideo=yes&id_video=' + $('.selected:first').find("input").attr("idobject");	
				} else if ($("#type").val()=='object') {	
				var url=$("#ref").val() + '&ajax=true&previewMode=' + $("#mode").val() + '&setObject=yes&id_object2=' + $('.selected:first').find("input").attr("idobject");	
				} else {
				if (all==1) {
					var url=$("#ref").val() + '&ajax=true&previewMode=' + $("#mode").val() + '&setPreview=yes&id_image=' + $('.selected:first').find("input").attr("idobject");
				} else {
					//множественный выбор
					var id_str='';
					$('.selected').each(function(index) {
						id_str=id_str + '&id_image[]=' + $(this).find("input").attr("idobject");
					});
					var url=$("#ref").val() + '&ajax=true&previewMode=' + $("#mode").val() + '&setPreview=yes' + id_str;
				}
				}
				location.href=url;
			}
		} else {
			if ($("#type").val()=='video') {
				alert('Вы не выбрали ни одного видео!');
			} else if ($("#type").val()=='object') {
				alert('Вы не выбрали ни одного объекта!');
			} else {
				alert('Вы не выбрали ни одного изображения!');
			}
		}	
	}
	
	function show_hide_form(elementID)	{
		if ($('#'+elementID).css('display')=='none') {
			$('#'+elementID).show();
		} else {
			$('#'+elementID).hide();
		}
	}
	
	function vv_print(id1,id2,mmin,mmax) {
		var el=document.getElementById(id1);
		var dl= el.value.length;
		var el2=document.getElementById(id2);
		if ((dl>=mmin) && (dl<=mmax)) {
			el2.innerHTML='<font style=color:#99cc00;>Вы ввели ' + dl + ' символов</font>';
		} else {
			 if ((mmin==0) && (mmax==0)) {
		  el2.innerHTML='<font style=color:#99cc00;>Вы ввели ' + dl + ' символов</font>';
		 } else {
		  if (dl<mmin) {
		   el2.innerHTML='<font style=color:#cc3300;<>Вы ввели ' + dl + ' символов (недобор - ' + (mmin-dl) + ' символа(ов))</font>';	
		  } else {
		   el2.innerHTML='<font style=color:#cc3300;<>Вы ввели ' + dl + ' символов (перебор - ' + (dl-mmax) + ' символа(ов))</font>';		  
		  }
		 }
		}
	}
		
		function getCurrentTime() {
			var time=new Date();
			var time_hours=time.getHours(time);
			var time_minutes=time.getMinutes(time);
			if (time_minutes<10) time_minutes='0' + time_minutes;
			if (tchk) {
				var time_str=time_hours + ':' + time_minutes;
				tchk=false;
			} else {
				var time_str=time_hours + ' ' + time_minutes;			
				tchk=true;
			}
			$("#time").html(time_str);
			var month=time.getMonth()+1;
			if (month<10) {
				var month_str='0' + month;
			} else {
				var month_str=month;
			}
			var day=time.getDate();
			if (day<10) {
				var day_str='0' + day;
			} else {
				var day_str=day;
			}			
			var date_str=day_str+'.'+month_str+'.'+time.getFullYear();
				if ($("#date").html()!=date_str) $("#date").html(date_str);
			/*reminders*/
			if (has_reminders) {
				if (show_zv) {
					document.title='************************************************';
					show_zv=false;
				} else {
					document.title=title;
					show_zv=true;
				}
			} else {
				if (show_zv==false)
					document.title=title;
			}
			setTimeout(getCurrentTime,1000);
		}	
		
		function delObj(id_object) {
			$("#obj" + id_object).empty();
			$("#obj" + id_object).die();
			$("#obj" + id_object).hide();		
		}		
		
		function delImg(id_image) {
			$("#img" + id_image).empty();
			$("#img" + id_image).die();
			$("#img" + id_image).hide();		
		}

		function delVideo(id_video) {
			$("#video" + id_video).empty();
			$("#video" + id_video).die();
			$("#video" + id_video).hide();		
		}

		function setObj(caption,id_object2,id_object) {
			var clss=$(".pictures tr:last").attr('class');
			if (clss=='objects_cell_bold') {
				clss='objects_cell_light';
			} else if (clss=='objects_cell_light') {
				clss='objects_cell_bold';
			} else {
				clss='objects_cell_bold';
			}
			var img_html='<tr class="' + clss + '" id="obj' + id_object2 + '"><td id="preview' + id_object2 + '">' + caption + '</td><td align="center"><input type="text" class="textbox" name="sort[' + id_object2 + ']" value="0" size="3"></td><td align="center"><input type="checkbox" name="del[' + id_object2 + ']" class="deletecheckbox" numb="0"></td><td align="center"><a href="/admin/?module=modules&module_name=objects&modAction=settings&m_action=dialogobject&id_object=' + id_object + '&ajax=true&get_rubrics=true&mode=' + id_object2 + '" class="change">изменить</a></td></tr>';
			$(".toDel").empty();
			$(".toDel").die();	
			$(".pictures").append(img_html);
			$(".pictures").show();
			$(".pictures2").removeClass('nonvisible');
		}
		
		function setObj2(caption,id_object2,id_object) {
			var clss=$(".pictures tr:last").attr('class');
			if (clss=='objects_cell_bold') {
				clss='objects_cell_light';
			} else if (clss=='objects_cell_light') {
				clss='objects_cell_bold';
			} else {
				clss='objects_cell_bold';
			}
			var img_html='<tr class="' + clss + '" id="obj' + id_object2 + '"><td id="preview' + id_object2 + '">' + caption + '</td><td align="center"><input type="text" class="textbox" name="sort[' + id_object2 + ']" value="0" size="3"></td><td align="center"><input type="checkbox" name="del[' + id_object2 + ']" class="deletecheckbox" numb="0"></td><td align="center"><a href="/admin/?module=modules&module_name=objects&modAction=settings&m_action=dialogobject&id_object=' + id_object + '&ajax=true&get_rubrics=true&mode=' + id_object2 + '" class="change">изменить</a></td></tr>';
			$(".toDel").empty();
			$(".toDel").die();	
			$(".pictures").append(img_html);
			$(".pictures").show();
			$(".pictures2").removeClass('nonvisible');
		}		
		
		function setImg(path,img,id_image,id_category) {
			var clss=$(".pictures tr:last").attr('class');
			if (clss=='objects_cell_bold') {
				clss='objects_cell_light';
			} else if (clss=='objects_cell_light') {
				clss='objects_cell_bold';
			} else {
				clss='objects_cell_bold';
			}
			var img_html='<tr class="' + clss + '" id="img' + id_image + '"><td id="preview' + id_image + '"><a href="/admin/?module=objects&amp;modAction=crop&amp;filename_photo=' + img + '&amp;ajax=true" class="crop"><img src="' + path +  img + '" border="0"></a></td><td align="center"><input type="text" class="textbox" name="sort[' + id_image + ']" value="0" size="3"></td><td align="center"><input type="checkbox" name="del[' + id_image + ']" class="deletecheckbox" numb="0"></td><td align="center"><a href="/admin/?module=objects&modAction=changepreview&ajax=true&mode=' + id_image + '&id_category=' + id_category + '&get_rubrics=true" class="change">изменить</a></td></tr>';
			$(".toDel").empty();
			$(".toDel").die();	
			$(".pictures").append(img_html);
			$(".pictures").show();
			$(".pictures2").removeClass('nonvisible');
		}

		function setVideo(path,caption,id_video,id_category) {
			var clss=$(".pictures tr:last").attr('class');
			if (clss=='objects_cell_bold') {
				clss='objects_cell_light';
			} else if (clss=='objects_cell_light') {
				clss='objects_cell_bold';
			} else {
				clss='objects_cell_bold';
			}
			var img_html='<tr class="' + clss + '" id="video' + id_video + '"><td id="preview' + id_video + '"><a href="' + path + id_video + '&ajax=true" class="video">' + caption + '</a></td><td align="center"><input type="text" class="textbox" name="sort[' + id_video + ']" value="0" size="3"></td><td align="center"><input type="checkbox" name="del[' + id_video + ']" class="deletecheckbox" numb="0"></td><td align="center"><a href="/admin/?module=objects&modAction=changepreview&ajax=true&mode=' + id_video + '&id_category=' + id_category + '&get_rubrics=true&type=video" class="change">изменить</a></td></tr>';
			$(".toDel").empty();
			$(".toDel").die();	
			$(".pictures").append(img_html);
			$(".pictures").show();
			$(".pictures2").removeClass('nonvisible');
		}
		
		function tooltip(title,txt,timeout) {
			if (title!='') {
				txt='<h3>' + title + '</h3>' + txt;
			}
			$("#hidden_tooltip .tooltip_body").html(txt);
			$('#hidden_tooltip .tooltip').clone().appendTo('#visible_tooltips').fadeIn(500, function() {
				var obj_id=this;
				if (timeout>0) setTimeout(function() { 
					$(obj_id).fadeOut(1200, function () {
						$(this).empty();
						$(this).die();
					});
				 },timeout);
      		});
		}
		
		function hideTooltip(tId) {
			$(tId).fadeOut(300, function() {
				$(this).empty();
				$(this).die();
			});
		}
		

function start_copy(max_obj,count_obj,objects) {
if (max_obj>count_obj) {
 copy_object(objects[count_obj]);
 count_obj++;
} else {
 alert('копирование завершено!');
}
}

function copy_object(number) {
 loadContent('&id_object='+number,'copy_container',null);
}

function select_icon(obj,type) {
	var o=document.getElementById(obj);
	var o2=document.getElementById('chk' + obj);
	if (o.className=='selected_icon' || o.className=='selected_preview') {
		if (type==1) {
		o.className='icon2';
		} else {
		o.className='preview';
		}
		o2.value=0;
	} else {
		if (type==1) {
		o.className='selected_icon';
		} else {
		o.className='selected_preview';
		}
		o2.value=1;
	}
}

function highlightMeForm(id) {
	try {
	new Effect.Highlight(id, { startcolor: '#fffbf0', endcolor: '#F8F8F8'});	
	} catch(e) {}
}

function highlightMe(id) {
	try {
	new Effect.Highlight(id, { startcolor: '#ffff99', endcolor: '#F8F8F8'});	
	} catch(e) {}
}

function promptUrl(mess,url,v) {
	var p=prompt(mess);
	if (p>0 && p<=99) {
		location.href=url + '&' + v + '=' +p;
	}
}

function goTo(url) {
	//переходит на нужный url
	location.href=url;
	return false;
}

function YesNo(message,url) {
	//спрашивает да или нет и совершает действие
	if (confirm(message)) location.href=url;
	return false;
}

function YesNoAjax(message,url,object) {
	//спрашивает да или нет и совершает действие
	if (confirm(message)) loadContent(url,object);
	return false;
}

function YesNoAjaxForm(message,url,object,frm) {
	//спрашивает да или нет и совершает действие
	if (confirm(message)) loadContentForm(url,object,frm,null);
	return false;
}

function showCoords(c) {
  // variables can be accessed here as
  // c.x, c.y, c.x2, c.y2, c.w, c.h
  $("#x").val(c.x);
  $("#y").val(c.y);
  $("#x2").val(c.x2);
  $("#y2").val(c.y2);
  $("#w").val(c.w);  
  $("#h").val(c.h);
}

function cropMe(v) {
		var w=$("#" + v + "_w").val();
		var h=$("#" + v + "_h").val();
		var x=$("#" + v + "_x").val();
		var y=$("#" + v + "_y").val();	
		var x2=$("#" + v + "_x2").val();
		var y2=$("#" + v + "_y2").val();	
		var width=$("#" + v + "_width").val();
		var height=$("#" + v + "_height").val();			
		$("#width").val(width);
		$("#height").val(height);
		var ratio=1;
		if (width>0 && height>0) {
			ratio=width/height;
		} else {
			if (width==0) {
				width=height;
			}
			if (height==0) {
				height=width;
			}			
		}
		if (crop_api==null) {
		 crop_api = $.Jcrop('#cropbox',{ 
				onSelect:    showCoords,
				boxWidth: 828,
				boxHeight: 658,
				setSelect: [x,y,x2,y2],
				aspectRatio: ratio		
		 });
		} else {
			crop_api.release();
			//crop_api.setSelect([x,y,x2,y2]);
			crop_api.animateTo([x,y,x2,y2]);
			crop_api.setOptions({ aspectRatio: ratio });
		}
		$(".croptable").css("visibility","visible");		
}

function loadContent(url,object,effect_id) {
new Ajax.Request(url,
  {
  	encoding:'windows-1251',
    method:'get',
	contentType:'application/x-www-form-urlencoded',
	onCreate: function() {
	var notice2 = $(object);
	notice2.update('<table style=\"height:250px;width:100%;\"><tr><td valign=middle align=center><img src=\"../images/admin/ajax-loader.gif\"></td></tr></table>');
	 },	
    onSuccess: function(transport) {
      var response = transport.responseText || "Не получено ответа от сервера";
		var notice = $(object);
		notice.update(response);
    },	 
    onComplete: function(transport) {
      var response = transport.responseText || "Ошибка загрузки";
		var notice = $(object);
		notice.update(response);
		
		try {
		myLightWindow.deactivate();	
		myLightWindow=null;
		myLightWindow=new lightwindow();
		highlightMe(effect_id);
		} catch(e) {}
		
    },
    onFailure: function(){ alert('{$lang.ajax.error_loading}') }
  });
}

function loadContentForm(url,object,form,effect_id) {
new Ajax.Request(url,
  {
  	encoding:'windows-1251',
    method:'post',
	parameters: $(form).serialize(true),
	contentType:'application/x-www-form-urlencoded',
	onCreate: function() {
	var notice2 = $(object);
	notice2.update('<table style=\"height:250px;width:100%;\"><tr><td valign=middle align=center><img src=\"../images/admin/ajax-loader.gif\"></td></tr></table>');
	 },	
    onSuccess: function(transport) {
      var response = transport.responseText || "Не получено ответа от сервера";
		var notice = $(object);
		notice.update(response);
    },
    onComplete: function(transport) {
      var response = transport.responseText || "Не получено ответа от сервера";
		var notice = $(object);
		notice.update(response);
		try {
		myLightWindow.deactivate();	
		myLightWindow=null;
		myLightWindow=new lightwindow();
		highlightMe(effect_id);
		} catch(e) {}		
    },	
    onFailure: function(){ alert('Ошибка загрузки') }
  });
}

//открыть ссылку в новом окне
	function open_window(link,w,h) 
	{
		var win = "width="+w+",height="+h+",menubar=no,location=no,resizable=yes,scrollbars=yes";
		newWin = window.open(link,'newWin',win);
		newWin.focus();
	}

//показать диалог загрузки
	function showLoad(capt) {
		$("#load_caption").html(capt);
		$("#load").fadeIn(700);
	}
	
//устанавливаем название текущего процесса
	function setLoadProcess	(mess) {
		$("#load_message").html(mess);
	}
	
//скрыть диалог загрузки
	function hideLoad(capt) {
		$("#load").fadeOut(700);
	}	

//сменить название у объекта галереи
	function renameItem(obj,value) {
		$('#' + obj).find('.preview_caption').html(value);
	}

//получить напоминания и известия о новых событиях в системе
	function getReminders() {
		$.ajax({
		  url: '/admin/?module=notes&modAction=get_new',
  		  success: function(data) {
			try {
				var reminders = jQuery.parseJSON(data);
				has_reminders=false;
				for (reminder in reminders) {
					if (reminders[reminder].subject!=null && reminders[reminder].content!=null)  {
						var time=0;
						if (reminders[reminder].time>0) 
							time=reminders[reminder].time;
						tooltip(reminders[reminder].subject,reminders[reminder].content,time);
has_reminders=true;
					}		
					if (reminders[reminder].count!=null && reminders[reminder].module!=null)  {
						$('#favorite_' + reminders[reminder].module + ' .favorite_reminder').html(reminders[reminder].count);
						$('#favorite_' + reminders[reminder].module + ' .favorite_reminder').fadeIn(200);
						if (reminders[reminder].silent!=true) {
							has_reminders=true;
						}
					}	
				}
				if (has_reminders==false) {
					document.title=title;
				}				
			} catch(e) {};
				$.ajax({
				  url: '/admin/?module=notes&modAction=delete_reminders',
		  		  success: function(data) {
					setTimeout(getReminders,60000);
				  }
				});
		  }
		});
	}
	
	function setProcess(btn) {
	var id_process=$(btn).attr("id_process");
	 	if (id_process>0) {
			setLoadProcess('Пожалуйста подождите');
			showLoad('Органайзер');
			$.post('/admin/?module=notes&modAction=setDoAffair&id_process=' + id_process,function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "В процессе загрузки произошла ошибка: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			 } else {
				hideLoad();
				switch (response) {
					case 'ERROR':
						tooltip('Ошибка','При обновлении информации о деле произошла ошибка, пожалуйста попробуйте повторить запрос',0);
					break;
					default:
						$('#btn_' + id_process).html('Выполнено <b>' + response + '</b>');
				}
			 }
			});
		} else {
			tooltip('Ошибка','Неверно указан идентификатор дела',5000);
		}	
	}
	
	function delProcess(btn) {
	var id_process=$(btn).attr("id_process");
	 	if (id_process>0) {
			setLoadProcess('Пожалуйста подождите');
			showLoad('Органайзер');
			$.post('/admin/?module=notes&modAction=setDelAffair&id_process=' + id_process,function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "В процессе загрузки произошла ошибка: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			 } else {
				hideLoad();
				switch (response) {
					case 'ERROR':
						tooltip('Ошибка','При удалении дела произошла ошибка, пожалуйста попробуйте повторить запрос',0);
					break;
					case 'DEL':
						$("#affair" + id_process).empty();
						$("#affair" + id_process).detach();
					break;
					default:
						tooltip('Ошибка','При удалении дела произошла ошибка',5000);
				}
			 }
			});
		} else {
			tooltip('Ошибка','Неверно указан идентификатор дела',5000);
		}	
	}	
	
	function saveButton(btn,parent) {
		if (parent!='' && parent!=null) {
		var str='/admin/?save=yes&parent=' + parent;
		} else {
		var str='/admin/?save=yes';
		}
		if ($(btn).attr('url')!=null) {
			str=str + '&url=' + $(btn).attr('url');
		}
		if ($(btn).attr("idobject")!=null) {
			str=str + '&idobject=' + $(btn).attr('idobject');
		}
		var offset=$(btn).position();
		var top = offset.top;
		str=str + '&left=' + offset.left + '&top=' + top + '&type=1&caption=' + $(btn).attr("caption");
		$.ajax({
		  url: str,
		  success: function(data) {
		    switch (data) {
				case 'error':
					tooltip('','При обновлении данных произошла ошибка',5000);
				break;
				default:
					try {
						var object = jQuery.parseJSON(data);
					} catch(e) {};
					$("#properties").fadeOut(500);
					if (object==null) {
						tooltip("Ошибка","При обновлении произошла ошибка, данные не сохранены",0);
					} else {
						tooltip('','Кнопка добавлена',2000);
					}				
			}
		  }
		});
	}
	
	$(document).ready(function(){
		getCurrentTime();
		setTimeout(getReminders,1000);	
		setTimeout(showStartupTooltips,500);
		$('.editable').dblclick(function() {
			  $(this).find("span").html('');
			  $(this).find("input").addClass('textbox');
			  $(this).find("input").fadeIn(200);
			  $(this).find("select").addClass('textbox');
			  $(this).find("select").fadeIn(200);			  
			  $("#save_submit").fadeIn(200);
		});

		$("form").submit(function () { 
			var n = $(".deletecheckbox:checked").length;
			var m = $(".selectall .deletecheckbox:checked").length;
			if (n>0) {
				if (!confirm('Вы отметили ' + (n-m) + ' элемент(ов) для удаления. При нажатии кнопки да все они будут удалены. Продолжить?')) {
					return false;
				}
			}
		});

		$('.selectall input:checkbox').click(function() {
			  var numb=$(this).attr("numb");
			  $(".checkbox[numb=" + numb + "]").attr("checked",$(this).attr("checked"));
			  $("#save_submit").fadeIn(200);
		});

		$('.selectall input.deletecheckbox:checkbox').click(function() {
			  var numb=$(this).attr("numb");
			  $(".deletecheckbox[numb=" + numb + "]").attr("checked",$(this).attr("checked"));
			  $("#save_submit").fadeIn(200);
		});
				
		$('.deletecheckbox').click(function() {		
			  $("#save_submit").fadeIn(200);
		});
		
		$('.checkbox').click(function() {
			  $("#save_submit").fadeIn(200);
		});

		$('.deletecheckbox').live('click', function() {
			  $(".pictures2").removeClass("nonvisible");
			  $("#save_submit").fadeIn(200);
		});
		
		$('.checkbox').live('click', function() {
			  $("#save_submit").fadeIn(200);
		});

		$('.textbox').live('keypress', function() {
			 $("#save_submit").fadeIn(200);
		});

		$('.textbox').keypress(function() {
			 $("#save_submit").fadeIn(200);
		});	

		$('.tt').click(function() {
			  $('.icons[type=image]').append('test');
		});
		
		$('.unread').click(function() {
			$(this).parent().html('0');
		});

		$('.change').live('click',function() {
			var href=$(this).attr("href");
			$.fancybox({
				'href'			: href,
				'width'				: '90%',
				'height'			: 630,
		        'autoScale'     	: true,
		        'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe'
			});	
			return false;
		});

		$('.crop').live('click', function() {
			var href=$(this).attr("href");
			$.fancybox({
		'href'			: href,
		'width'				: 880,
		'height'			: 800,
		'titleShow'		: false,
        'autoScale'     	: false,
        'scrolling'			: 'no',
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe',
		'onStart'		:   function() {
			show_close_dialog=true;
		},
		'onCleanup'	:	function() {
			if (show_close_dialog) {
				if (!confirm('Закрыть диалог? Все внесенные Вами изменения могут быть потеряны!')) {
					return false;
				}
			}
		}		
			});	
			return false;			
		});

		$('.dialog').live('click', function() {
			var href=$(this).attr("href");
			$.fancybox({
		'href'			: href,
		'width'				: '60%',
		'height'			: '60%',
		'titleShow'		: false,
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
			});	
			return false;			
		});			
		
		$('.video').live('click', function() {
			var href=$(this).attr("href");
	$.fancybox({
		'href'					:href,
		'width'				: 400,
		'height'			: 300,
		'titleShow'		: false,
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe',
		'onCleanup'	:	function() {
			if (!confirm('Прервать воспроизведение?')) {
				return false;
			}
		}		
	});				
			return false;			
		});
		
		$('.tooltip').live('click', function() {
		  hideTooltip(this);
		});
		
	/*настройка fancybox*/
	$("a.editor").fancybox({
		'width'				: '70%',
		'height'			: 520,
        'autoScale'     	: false,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe',
		'hideOnOverlayClick' : false,
		'hideOnContentClick': false,
		'onStart'		:   function() {
			show_close_dialog=true;
		},
		'onCleanup'	:	function() {
			if (show_close_dialog) {
				if (!confirm('Закрыть диалог? Все внесенные Вами изменения могут быть потеряны!')) {
					return false;
				}
			}
		}
	});	
	
	/*функции галереи*/
	$('#selectImage').click(function() {
		selectImage();
	});

	$('#deleteImage').click(function() {
		if ($("#type").val()=='video') {
			var confirm_text='Удалить видео?';
		} else if ($("#type").val()=='object') {
			var confirm_text='Удалить объект?';
		} else {
			var confirm_text='Удалить превью?';
		}	
			if (confirm(confirm_text)) {
				if ($("#type").val()=='video') {
				var url=$("#ref").val() + '&ajax=true&previewMode=' + $("#mode").val() + '&setVideo=yes&id_video=0';
				} else if ($("#type").val()=='object') {
				var url=$("#ref").val() + '&ajax=true&previewMode=' + $("#mode").val() + '&setObject=yes&id_object2=0';
				} else {
				var url=$("#ref").val() + '&ajax=true&previewMode=' + $("#mode").val() + '&setPreview=yes&id_image=0';
				}
				location.href=url;
			}
	});		
	
	$('#previewcreate').click(function() {
		if (confirm('Сохранить превью?')) {
			$("#frm").submit();
		}
	});
	
	$('#previewcreate2').click(function() {
		if (confirm('Сохранить превью?')) {
			$("#frm").submit();
		}
	});	
		
	$('#previewlist').change(function() {
	  if (confirm('Изменить размер превью? Все сделанные на данный момент изменения будут потеряны!')) {
	  	var v=$('#previewlist option:selected').val();
		cropMe(v);
	  }
	});	

	$('#setrandomsize').click(function() {
	  if (confirm('Изменить размер превью? Все сделанные на данный момент изменения будут потеряны!')) {
		var w=$("#width").val();
		var h=$("#height").val();
		var x=0;
		var y=0;	
		var x2=w;
		var y2=h;	
		var ratio=1;
		if (w>0 && h>0) {
			ratio=w/h;
		} else {
			if (w==0) {
				w=h;
			}
			if (h==0) {
				h=w;
			}			
		}
		if (crop_api==null) {
		 crop_api = $.Jcrop('#cropbox',{ 
				onSelect:    showCoords,
				boxWidth: 828,
				boxHeight: 658,
				setSelect: [x,y,x2,y2],
				aspectRatio: ratio		
		 });
		} else {
			crop_api.release();
			//crop_api.setSelect([x,y,x2,y2]);
			crop_api.animateTo([x,y,x2,y2]);
			crop_api.setOptions({ aspectRatio: ratio });
		}
	  }
	});	
	
	$('#deletebutton').click(function() {	
		function doDelete() {
			if (current<all) {
			setLoadProcess('Перенос ' + (current+1) + ' из ' + all + ' объектов');	
			var idobject=$('.selected:first').find("input").attr("idobject");
			
			$.post('/admin/?module=objects&modAction=delete&objects[' + idobject + ']=1', function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "В процессе загрузки произошла ошибка: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			 } else {
			 	$("#obj" + idobject).empty();
			 	$("#obj" + idobject).detach();			 	
			 	current++;
			 	doDelete();
			 }
			 		 
			});	
			} else {
				hideLoad();
				tooltip('Удаление завершено','Было удалено ' + current + ' объектов',5000);
			}
		}
		var all=$('.selected').length;
		var current=0;
		var id_category=$(this).attr("idcat");
		if (all>0) {
			if (confirm('ВНИМАНИЕ! Удаляемые данные в случае согласия будут потеряны навсегда!\nУдалить выделенные объекты?')) {
				showLoad('удаление объектов');
				doDelete();
			}
		} else {
			tooltip('Ошибка','Вы не выделили ни одного объекта для удаления!',5000);
		}
	});
	
	$('.copy_objects').click(function() {	
		function doLoad() {
			if (current<all) {
			setLoadProcess('Копирование ' + (current+1) + ' из ' + all + ' объектов');	
			var idobject=$('.selected:first').find("input").attr("idobject");
			
			$.post("/admin/?module=objects&modAction=move&create_thumbnails=yes&delete_files=yes&id_cat="+id_category + "&objects[" + idobject + "]=1", function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "В процессе загрузки произошла ошибка: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			 } else {
			 	$("#obj" + idobject).empty();
			 	$("#obj" + idobject).detach();			 	
			 	current++;
			 	doLoad();
			 }
			 		 
			});	
			} else {
				hideLoad();
				tooltip('Копирование завершено','Было скопировано ' + current + ' объектов',5000);
			}
		}
		var all=$('.selected').length;
		var current=0;
		var id_category=$(this).attr("idcat");
		if (all>0) {
			if (confirm('Перенести выделенные объекты?')) {
				showLoad('конвертация объектов');
				doLoad();
			}
		} else {
			tooltip('Ошибка','Вы не выделили ни одного объекта для копирования в галерею!',5000);
		}
	});	

	$('#deleteitemsbutton').click(function() {	
		function doLoad() {
			if (current<all) {
			setLoadProcess('Удаление ' + (current+1) + ' из ' + all + ' объектов');	
			var idobject=$('.selected:first').find("input").attr("idobject");
			var mass=$('.selected:first').find("input").attr("objtype");
			
			$.post("/admin/?module=objects&modAction=delete_objects&id_cat="+id_category + "&" + mass + "[" + idobject + "]=1", function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "В процессе загрузки произошла ошибка: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			 } else {
			 	$("#" + mass + idobject).empty();
			 	$("#" + mass + idobject).detach();			 	
			 	current++;
			 	doLoad();
			 }
			 		 
			});	
			} else {
				hideLoad();
				tooltip('Удаление завершено','Было удалено ' + current + ' объектов',5000);
			}
		}
		var all=$('.selected').length;
		var current=0;
		var id_category=$(this).attr("idcat");
		if (all>0) {
			if (confirm('ВНИМАНИЕ! Удаляемые данные в случае согласия будут потеряны навсегда!\nУдалить выделенные объекты?')) {
				showLoad('удаление объектов');
				doLoad();
			}
		} else {
			tooltip('Ошибка','Вы не выделили ни одного объекта для удаления!',5000);
		}
	});	

	$('.move_objects').click(function() {	
		function doLoad() {
			if (current<all) {
			setLoadProcess('Перемещение ' + (current+1) + ' из ' + all + ' объектов');	
			var idobject=$('.selected:first').find("input").attr("idobject");
			var mass=$('.selected:first').find("input").attr("objtype");
			
			$.post("/admin/?module=objects&modAction=move_id_cat&id_cat="+id_category + "&" + mass + "[" + idobject + "]=1", function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "В процессе загрузки произошла ошибка: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			 } else {
			 	$("#" + mass + idobject).empty();
			 	$("#" + mass + idobject).detach();			 	
			 	current++;
			 	doLoad();
			 }
			 		 
			});	
			} else {
				hideLoad();
				tooltip('Перемещение завершено','Было перемещено ' + current + ' объектов',5000);
			}
		}
		var all=$('.selected').length;
		var current=0;
		var id_category=$(this).attr("idcat");
		if (all>0) {
			if (confirm('Переместить выделенные объекты?')) {
				showLoad('перемещение объектов');
				doLoad();
			}
		} else {
			tooltip('Ошибка','Вы не выделили ни одного объекта для перемещения!',5000);
		}
	});	
	
	$('.preview').live('dblclick', function() {
		if ($("#multiple").val()=='no') {
			$(".icons").find(".preview").removeClass('selected');
			$(".icons").find("input").val('0');
		}
		$(this).toggleClass('selected');
		selectImage();
	});
	
	$('.preview').live('click', function() {
		//если нужно выделить только 1 объект, остальные убираем
		if ($("#multiple").val()=='no') {
			$(".icons").find(".preview").removeClass('selected');
			$(".icons").find("input").val('0');		
		}
		$(this).toggleClass('selected');
		if ($(this).find("input").val()=='1') {
			$(this).find("input").val('0');
		} else {
			$(this).find("input").val('1');
		}
	});	

	$('.preview').mouseenter(function() {
		$(this).find('.preview_bar').fadeIn(100);
	}).mouseleave(function(){
		$(this).find('.preview_bar').fadeOut(100);
    });		

	$('.deselect_all_objects').click(function() {
		var type=$(this).attr("type");
		$(".icons[type=" + type + "]").find(".preview").removeClass('selected');
		$(".icons[type=" + type + "]").find("input").val('0');
	});				
	
	$('.select_all_objects').click(function() {
		var type=$(this).attr("type");
		$(".icons[type=" + type + "]").find(".preview").removeClass('selected');
		$(".icons[type=" + type + "]").find(".preview").addClass('selected');
		$(".icons[type=" + type + "]").find("input").val('1');		
	});	
	
	$('.changelink').click(function() {	
		$('.active').removeClass("active");
		$(this).toggleClass("active");	
		var target=$(this).attr("targ");
		var url=$(this).attr("url");
		$('#' + target).html(ajaxloader);
		$('#' + target).load(url,function(response) {
			$(this).html(response);
		});
	});				
	
	$('.ch').click(function() {	
		if (!confirm('Перейти к выбору превью? Все текущие изменения будут потеряны!')) {
			return false;
		}
	});	
	
	/*конец функций галереи*/
	
	$("a.external").fancybox({
		'width'				: '90%',
		'height'			: '90%',
        'autoScale'     	: false,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});

	$("a.change").fancybox({
		'width'				: '90%',
		'height'			: 630,
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});
	
	$(".dialog").fancybox({
		'width'				: '60%',
		'height'			: '60%',
		'titleShow'		: false,
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});	
	
	$(".crop").fancybox({
		'width'				: 880,
		'height'			: 800,
		'titleShow'		: false,
        'autoScale'     	: false,
        'scrolling'			: 'no',
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe',
		'onStart'		:   function() {
			show_close_dialog=true;
		},
		'onCleanup'	:	function() {
			if (show_close_dialog) {
				if (!confirm('Закрыть диалог? Все внесенные Вами изменения могут быть потеряны!')) {
					return false;
				}
			}
		}		
	});	

	$("a.video").fancybox({
		'width'				: 400,
		'height'			: 300,
		'titleShow'		: false,
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe',
		'onCleanup'	:	function() {
			if (!confirm('Прервать воспроизведение?')) {
				return false;
			}
		}		
	});	

	$("a.audio").fancybox({
		'width'				: 400,
		'height'			: 18,
		'titleShow'		: false,
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe',
		'onCleanup'	:	function() {
			if (!confirm('Прервать воспроизведение?')) {
				return false;
			}
		}		
	});	
	
	$("a.group").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false
	});	
	/*конец настройки fancybox*/			

		$('.show_hidden').change(function() {
			var ident=$(this).attr("ident");
			var type=$(this).attr("generate_type");
			var str='';
			if ($(this).val()!='') {
			$(".hidden_element[ident=" + ident + "]").fadeIn(500);
			switch (type) {
				case 'blocks':
					str='{$blocks.'+$(this).val()+'.content}';
				break;
				case 'allblocks':
					if ($(this).val()=='all') {
						str='{foreach key=key item=block from=$blocks}\n\t{$block.content}\n{/foreach}';
					}					
				break;
				case 'allmodules':
					if ($(this).val()=='all') {
						str='{foreach key=key item=module from=$modules}\n\t{$module.content}\n{/foreach}\n{foreach key=key item=module from=$static_modules}\n\t{$module.content}\n{/foreach}';
					}					
				break;
				case 'path':
					if ($(this).val()=='all') {
						str='{foreach key=key item=pth from=$real_path}\n\t{if $key>0 && !$pth.is_last} > {/if}\n\t\t{if $pth.is_last}\n\t\t\t<img src="{$img_theme}r.jpg">\n\t\t{else}\n\t\t\t<a href="{if $pth.rss_link}{$pth.rss_link}{else}{$pth.url}{/if}">{$pth.caption}</a>\n\t\t{/if}\n{/foreach}';
					}					
				break;
				case 'rubrics':
					if ($(this).val()=='razd') {
						var n=prompt('Пожалуйста укажите уровень от 0 и больше');
						if (n>=0) {
						str='<ul>\n\t{foreach key=key item=cat from=$rubrics.up}\n\t\t{if $cat.level==' + n + '}\n\t\t\t{if $cat.caption}\n\t\t\t\t<li><a href="{if $cat.rss_link}{$cat.rss_link}{else}{$cat.url}{/if}" {if $cat.selected && !$cat.main_page}class="selected"{/if}>{$cat.caption}</a></li>\n\t\t\t{/if}\n\t\t{/if}\n\t{/foreach}\n</ul>';
						} else {
						tooltip('Ошибка','Вы неверно указали уровень',3000);
						}
					}
					if ($(this).val()=='podrazd') {
						str='<ul>\n\t{if $page.level>0 && $page.categories>0}\n\t\t{foreach key=key item=cat from=$rubrics.up}\n\t\t\t{if $page.id_category==$cat.id_sub_category}\n\t\t\t\t<li>{if $cat.selected}<b>{$cat.caption}</b>{else}<a href="{if $cat.rss_link}{$cat.rss_link}{else}{$cat.url}{/if}">{$cat.caption}</a>{/if}</li>\n\t\t\t{/if}\n\t\t{/foreach}\n\t{else}\n\t\t{foreach key=key item=cat from=$rubrics.up}\n\t\t\t{if $page.id_sub_category==$cat.id_sub_category}\n\t\t\t\t<li>{if $cat.selected}<b>{$cat.caption}</b>{else}<a href="{if $cat.rss_link}{$cat.rss_link}{else}{$cat.url}{/if}">{$cat.caption}</a>{/if}</li>\n\t\t\t{/if}\n\t\t{/foreach}\n\t{/if}\n</ul>';
					}
					if ($(this).val()=='tree') {
						str='<ul>\n\t{foreach key=key item=cat1 from=$rubrics}\n\t\t{foreach key=key item=cat from=$cat1}\n\t\t\t<li style="padding-left: {math equation="x * y" x="5" y=$cat.level}px;list-style:none;"><a href="{if $cat.rss_link}{$cat.rss_link}{else}{$cat.url}{/if}" target="_blank">{$cat.caption}</a></li>\n\t\t{/foreach}\n\t{/foreach}\n</ul>';
					}					
				break;			
				case 'page':
					str='{$page.'+$(this).val()+'}';
				break;				
				case 'site':
					str='{$'+$(this).val()+'}';
				break;					
			}
			$(".hidden_element[ident=" + ident + "] textarea").val(str);
			}
		});
		
		/*notes*/
	$('#delo').keyup (function() {
		var str=$("#delo").val();
		$("#symbols").html('Вы ввели ' + str.length + ' символов');
	});
	
	$('#createAffair').click(function() {	
		if ($("#delo").val()!='' && $("#vazhn").val()!='') {
			setLoadProcess('Добавление нового дела');
			showLoad('Органайзер');
			$.post('/admin/?module=notes&modAction=createAffair&vazhn=' + $("#vazhn").val() + '&delo=' + $("#delo").val(),function(response, status, xhr) {
			  if (status == "error") {
			    var msg = "В процессе загрузки произошла ошибка: ";
			    alert(msg + xhr.status + " " + xhr.statusText);
				hideLoad();
			 } else {
				hideLoad();
			 	switch (response) {
					case 'WRONGDATA':
						tooltip('Ошибка','Переданы неверные параметры',5000);
					break;
					case 'ERROR':
						tooltip('Ошибка','Ошибка добавления нового дела',0);
					break;
					default:
					try {
						var object = jQuery.parseJSON(response);
					} catch(e) {};
					if (object==null) {
						tooltip("Ошибка","При добавлении дела произошла непредвиденная ошибка, пожалуйста повторите попытку добавления еще раз",0);
					} else {
					var s_object='<div id="affair' + object.id_process + '"><table><tr><td width="45%" class="proc">' + object.content + '</td><td width="10%" align="center"><input type="button" class="del_btn" id_process="' + object.id_process + '" value="удалить"></td><td width="20%" align="center">' + object.create_print + '</td><td width="25%" align="right" class="bigbutton" id="btn_' + object.id_process + '"><input type="button" class="subm_btn" id_process="' + object.id_process + '" value="Готово!"></td></tr></table></div>';
						$('.process[vazhn=' + object.vazhn + ']').append(s_object);
						$("#delo").val('');
						$("#symbols").html('');
						$("#clear").empty();
						$("#clear").detach();
					}
				}
			 }
			 });	
		} else {
			tooltip('Ошибка','Вы не ввели описание дела!',5000);
		}
	});		

	$('.subm_btn').live('click', function() {
		setProcess(this);
	});
	
	$('.del_btn').live('click', function() {
		if (confirm('Удалить дело?'))
			delProcess(this);
	});	
		/*end of notes*/
		
		/*categories*/
		$('.plusme img').click(function() {
			var idcat=$(this).attr("idcat");
			var parent=$(this).attr("idcat");
			  if ($(this).attr("mode")==1) {
			  	//если минус
				$(this).attr("src","/images/admin/icons/plus.png");
				$(this).attr("mode",0);
				$('.divblock[parent=' + idcat + ']').removeClass('category_float');
				$('.divblock[parent=' + idcat + ']').addClass('invisible_category');
			  } else {
			  	//если плюс
				$(this).attr("src","/images/admin/icons/minus.png");
				$(this).attr("mode",1);
				$('.divblock[parent=' + idcat + ']').removeClass('invisible_category');				
				$('.divblock[parent=' + idcat + ']').addClass('category_float');
			  }
		});		
		/*end of categories*/
		
	/*main*/
		/*
	$('.enable_main_edit').live('click', function() {
		$('#addbar').toggleClass('nonvisible');
		$("#sortable").toggleClass('edit');
		$('.work').removeClass('work');
		$('#properties').hide();
		if (mainMode==0) {
		$("#sortable").sortable({placeholder: 'ui-state-highlight'});
		$("#sortable").disableSelection();		
		mainMode=1;
		} else {
		
		mainMode=0;
		}
	});	
	
	$('.new_button').live('click', function() {
		var cls=$(this).attr("addclass");
		$('#sortable').append('<li class="ui-state-default ' + cls + '">Укажите название</li>');
	});
	

	$('#drag_first').live('click', function() {
		$('#drag_first').clone().appendTo('.edit td:first').fadeIn(500,function() {
			$(this).removeClass('actionbutton');
			$(this).addClass('drag_button');
			$(this).draggable();
			var n = $(".drag_button").length;
			$(this).attr("id","button_" + (n+1));
			$(this).html('Кнопка ' + (n+1));
			$(this).attr("caption",'Кнопка ' + (n+1));
		});
	});

	$('#sortable li').live('click', function() {
		if (mainMode==1) {
		//редактирование
		$("#caption").val($(this).html());
		if ($(this).attr("url")==null) {
		$("#url").val('http://');
		} else {
		$("#url").val($(this).attr("url"));
		}
		$("#sortable li").removeClass("work");
		$(this).addClass("work");
		var offset=$(this).offset();
		var left=offset.left-60;
		var top=offset.top-50;
		var cssObj = {
      'left' : left + 'px',
      'top' : top + 'px',
	    }
		$("#properties").css(cssObj);
		$("#properties").fadeIn(500);
		} else {
		//обработка клика
		}
	});		
	*/
	/*	
    $(".mybuttons td").droppable({
	accept: ".drag_button",
	activeClass: 'edit_hover',
	hoverClass: 'edit_hover',	
	out: function(event,ui) {
		if (confirm('Удалить кнопку ' + ui.draggable.attr("caption") + '?')) {
		  $.ajax({
		  url: '/admin/?delete=yes&idobject=' + ui.draggable.attr("idobject"),
		  success: function(data) {
		    switch (data) {
				case 'error':
					tooltip('','При удалении произошла ошибка',5000);
				break;
				case 'ok':
					tooltip('','Кнопка удалена',5000);
				break;
			}
			ui.draggable.fadeOut(300);
		  }
		 });			
		}
	},
    drop: function(event,ui) { 
		$("#properties").fadeOut(500);
		saveButton(ui.draggable,$(this).attr("id"));
	 }
    });

	$('#save_me').live('click', function() {
		$(".work").attr("url",$("#url").val());
		$(".work").attr("caption",$("#caption").val());
		$(".work").html($("#caption").val());
		$(".work").removeClass("work");
		$("#properties").fadeOut(500);
	});
	*/	
	/*end of main*/
	});
	
