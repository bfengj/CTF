
  function addTr(tab, row, trHtml){
     //??????table???????????? $("#tab tr:last")
     //??????table????????? $("#tab tr").eq(0)
     //??????table??????????????? $("#tab tr").eq(-2)
     var $tr=$("#"+tab+" tr").eq(row);
     if($tr.size()==0){
        alert("?????????table id?????????????????????");
        return;
     }
     $tr.after(trHtml);
  }
   
  function delTr(ckb){
     //???????????????????????????????????????????????????
     var ckbs=$("input[name="+ckb+"]:checked");
     if(ckbs.size()==0){
        alert("????????????????????????????????????????????????");
        return;
     }
           ckbs.each(function(){
              $(this).parent().parent().remove();
           });
  }

var shop_obj={

	pay_shipping_config_init:function(){
		
	 	global_obj.file_upload($('#CertUpload'), '', '', '', false, 1, function(filename,filepath){
			$('#web_payment_form input[name=CertPath]').val(filepath);
		}, '*.pem', '500KB');
		
		global_obj.file_upload($('#KeyUpload'), '', '', '', false, 1, function(filename,filepath){
			$('#web_payment_form input[name=KeyPath]').val(filepath);
		}, '*.pem', '500KB');
		
		global_obj.file_upload($('#PfxUpload'), '', '', '', false, 1, function(filename,filepath){
			$('#web_payment_form input[name=PfxPath]').val(filepath);
		}, '*.pfx', '500KB');
		
		global_obj.file_upload($('#CerUpload'), '', '', '', false, 1, function(filename,filepath){
			$('#web_payment_form input[name=CerPath]').val(filepath);
		}, '*.cer', '500KB');
		
		$("input.Default_Shipping").click(function(){
			var template_exist = $(this).parent().prev().find('select').length;
		
			if(template_exist == 0){
				alert('?????????????????????????????????');
				return false;
			}
		});
	
	    $('#shipping_default_config input:submit').click(function(){
			
			if(global_obj.check_form($('#shipping_default_config *[notnull]'))){
				return false;
			};
			
		});
		
	},
	
	skin_init:function(){
		$('#skin li .item').click(function(){
			if(!confirm('?????????????????????????????????')){return false};
			$.post('?', "do_action=shop.skin_mod&SId="+$(this).attr('SId'), function(data){
				if(data.status==1){
					window.location.reload();
				}
			}, 'json');
		});
	},
	
	category_init:function(){
		global_obj.file_upload($('#ImgUpload'), $('#category_form input[name=ImgPath]'), $('#ImgDetail'));
		
	},
	
	home_init:function(){
		//??????????????????
		global_obj.file_upload($('#HomeFileUpload'), $('#home_form input[name=ImgPath]'), $('#home .shop_skin_index_list').eq($('#home_form input[name=no]')).find('.img'));
		for(var i=0;i<5;i++){
			global_obj.file_upload($('#HomeFileUpload_'+i), $('#home_form input[name=ImgPathList\\[\\]]').eq(i), $('#home_form .b_r').eq(i));
		}
		
		$('.item .url_select .btn_select_url').click(function(){
			global_obj.create_layer('????????????', '/member/material/sysurl.php?dialog=1&input=shop_home_url_'+$(this).attr("ret"),1000,500);
		});
		
		$('.m_lefter a').attr('href', '#').css({'cursor':'default', 'text-decoration':'none'}).click(function(){
			$(this).blur();
			return false;
		});
		$('.m_lefter form').submit(function(){
			return false;
		});
		//??????????????????
		for(i=0; i<shop_skin_data.length; i++){
			var obj=$("#shop_skin_index div").filter('[rel=edit-'+shop_skin_data[i]['Postion']+']');
			obj.attr('no', i);
			if(shop_skin_data[i]['ContentsType']==1){
				var dataObj=eval("("+shop_skin_data[i]['ImgPath']+")");
				if(dataObj[0].indexOf('http://')!=-1){
					var s='';
				}else if(dataObj[0].indexOf('/u_file/')!=-1){
					var s=domain.img;
					dataObj[0]=dataObj[0].replace('/u_file', '');
				}else if(dataObj[0].indexOf('/api/')!=-1){
					var s=domain.static;
				}else{
					var s='';
				}
				obj.find('.img').html('<img src="'+s+dataObj[0]+'" />');
			}else{
				if(shop_skin_data[i]['ImgPath'].indexOf('http://')!=-1){
					var s='';
				}else if(shop_skin_data[i]['ImgPath'].indexOf('/u_file/')!=-1){
					var s=domain.img;
					shop_skin_data[i]['ImgPath']=shop_skin_data[i]['ImgPath'].replace('/u_file', '');
				}else if(shop_skin_data[i]['ImgPath'].indexOf('/api/')!=-1){
					var s=domain.static;
				}else{
					var s='';
				}
				if(shop_skin_data[i]['NeedLink']==1){
					obj.find('.text').html('<a href="">'+shop_skin_data[i]['Title']+'</a>')
				}else{
					obj.find('.text').html(shop_skin_data[i]['Title'])
				}
				obj.find('.img').html('<img src="'+s+shop_skin_data[i]['ImgPath']+'" />');
			}
		}
		
		$('.shop_skin_index_list div').after('<div class="mod">&nbsp;</div>');	//??????????????????
		$('#shop_skin_index .shop_skin_index_list').hover(function(){$(this).find('.mod').show();}, function(){$(this).find('.mod').hide();});
		
		//??????????????????????????????
		$('#shop_skin_index .shop_skin_index_list .mod').click(function(){
			var parent=$(this).parent();
			var no=parent.attr('no');
		
			$('#SetHomeCurrentBox').remove();
			parent.append("<div id='SetHomeCurrentBox'></div>");
			$('#SetHomeCurrentBox').css({'height':parent.height()-10, 'width':parent.width()-10})
			$("#setbanner, #setimages").hide();
			$('.url_select').css('display', shop_skin_data[no]['NeedLink']==1?'block':'none');
			
			if(shop_skin_data[no]['ContentsType']==1){
				$("#setbanner").show();
				var dataImgPath=eval("("+shop_skin_data[no]['ImgPath']+")");
				var dataUrl=eval("("+shop_skin_data[no]['Url']+")");
				var dataTitle=eval("("+shop_skin_data[no]['Title']+")");
				$('#home_form #setbanner .tips label').html(shop_skin_data[no]['Width']+'*'+shop_skin_data[no]['Height']);
				for(var i=0; i<dataImgPath.length; i++){
					$('#home_form input[name=ImgPathList\\[\\]]').eq(i).val(dataImgPath[i]);
					$('#home_form input[name=UrlList\\[\\]]').eq(i).val(dataUrl[i]);
					$('#home_form input[name=TitleList\\[\\]]').eq(i).val(dataTitle[i]);
					
					if(dataImgPath[i].indexOf('http://')!=-1){
						var s='';
					}else if(dataImgPath[i].indexOf('/u_file/')!=-1){
						var s=domain.img;
						dataImgPath[i]=dataImgPath[i].replace('/u_file', '');
					}else if(dataImgPath[i].indexOf('/api/')!=-1){
						var s=domain.static;
					}else{
						var s='';
					}
					dataImgPath[i] && $("#home_form .b_r").eq(i).html('<a href="'+s+dataImgPath[i]+'" target="_blank"><img src="'+s+dataImgPath[i]+'" /></a>');
					if(dataUrl[i]){
						$("#home_form select[name=UrlList\\[\\]]").eq(i).find("option[value='"+dataUrl[i]+"']").attr("selected", true);
					}else{
						$("#home_form select[name=UrlList\\[\\]]").eq(i).find("option").eq(0).attr("selected", true);
					}
				}
			}else{
				if(parent.find('.text').length){
					$("#setimages div[value=title]").show();
				}else{
					$("#setimages div[value=title]").hide();
				}
				if(parent.find('.img').length){
					$("#setimages div[value=images]").show();
				}else{
					$("#setimages div[value=images]").hide();
				}
				$("#setimages").show();
				$('#home_form input').filter('[name=Title]').val(shop_skin_data[no]['Title'])
				.end().filter('[name=ImgPath]').val(shop_skin_data[no]['ImgPath'])
				.end().filter('[name=Title]').focus();
				$('#home_form #setimages .tips label').html(shop_skin_data[no]['Width']+'*'+shop_skin_data[no]['Height']);
				if(shop_skin_data[no]['Url']){
					$("#home_form input[name=Url]").val(shop_skin_data[no]['Url']);
				}else{
					$("#home_form input[name=Url]").val("");
				}
			}	
					
			$('#home_form input').filter('[name=PId]').val(shop_skin_data[no]['PId'])
			.end().filter('[name=SId]').val(shop_skin_data[no]['SId'])
			.end().filter('[name=ContentsType]').val(shop_skin_data[no]['ContentsType'])
			.end().filter('[name=no]').val(no);
		});
		
		//??????????????????
		$('#shop_skin_index .shop_skin_index_list .mod').eq(0).click();
		
		//ajax?????????????????????
		$('#home_form').submit(function(){return false;});
		$('#home_form input:submit').click(function(){
			$(this).attr('disabled', true);
			$.post('?', $('#home_form').serialize()+'&do_action=shop.set_home_mod&ajax=1', function(data){
				$('#home_form input:submit').attr('disabled', false);
				if(data.status==1){
					$('#home_mod_tips .tips').html('?????????????????????');
					$('#home_mod_tips').leanModal();
					
					var _no=$('#home_form input[name=no]').val();
					var _v=$("div[no="+_no+"]");
					shop_skin_data[_no]['ImgPath']=data.ImgPath;
					shop_skin_data[_no]['Title']=data.Title;
					shop_skin_data[_no]['Url']=data.Url;
					
					if(shop_skin_data[_no]['ContentsType']==1){
						var dataImgPath=eval("("+shop_skin_data[_no]['ImgPath']+")");
						if(dataImgPath[0].indexOf('http://')!=-1){
							var s='';
						}else if(dataImgPath[0].indexOf('/u_file/')!=-1){
							var s=domain.img;
							dataImgPath[0]=dataImgPath[0].replace('/u_file', '');
						}else if(dataImgPath[0].indexOf('/api/')!=-1){
							var s=domain.static;
						}else{
							var s='';
						}
						_v.find('.img').html('<img src="'+s+dataImgPath[0]+'" />');
					}else{
						if(shop_skin_data[_no]['ImgPath'].indexOf('http://')!=-1){
							var s='';
						}else if(shop_skin_data[_no]['ImgPath'].indexOf('/u_file/')!=-1){
							var s=domain.img;
							shop_skin_data[_no]['ImgPath']=shop_skin_data[_no]['ImgPath'].replace('/u_file', '');
						}else if(shop_skin_data[_no]['ImgPath'].indexOf('/api/')!=-1){
							var s=domain.static;
						}else{
							var s='';
						}
						_v.find('.text').html('<a href="">'+shop_skin_data[_no]['Title']+'</a>');
						_v.find('.img').html('<img src="'+s+shop_skin_data[_no]['ImgPath']+'" />');
					}
				}else{
					$('#home_mod_tips .tips').html('?????????????????????????????????');
					$('#home_mod_tips').leanModal();
				};
			}, 'json');
		});
		
		$('#home_form .item .rows .b_l a[href=#shop_home_img_del]').click(function(){
			var _no=$(this).attr('value');
			$('#home_form .b_r').eq(_no).html('');
			$('#home_form input[name=ImgPathList\\[\\]]').eq(_no).val('');
			this.blur();
			return false;
		});
	
	},
	withdraw_method_init:function(){
		
		var method_name_rows = '<div style="display:block" class="rows method_name_rows"><label>??????</label><span class="input"><input value="" class="form_input" name="Method_Name" notnull="" type="text"></span><div class="clear"></div></div>'; 
	      
		$('#create_method_form input:submit').click(function(){
			if(global_obj.check_form($('#create_method_form *[notnull]'))){return false};
		});
		
		$(".method_edit_btn").click(function(){
			
			var Method_ID  = $(this).attr("method-id");
			var param = {'Method_ID':Method_ID,'action':'get_withdraw_edit_form'};
			
			$.get(base_url+'member/shop/ajax.php',param,function(data){
			   if(data.status == 1){
					$("#method_edit_content").html(data.content);
					$('#mod_edit_method').leanModal();
			   }
			},'json');
		});
		
		$("#create_method_btn").click(function(){
			$('#mod_create_method').leanModal();
		});
		
		$("input[name='Method_Type']").live('click',function(){
			
			var Method_Type = $(this).attr('value');
			
			if(Method_Type == 'bank_card'){
				if($(this).parent().parent().siblings(".method_name_rows").length == 0){
					$(this).parent().parent().after(method_name_rows);
				}
			}else if(Method_Type == 'alipay'){
				$(this).parent().parent().siblings(".method_name_rows").remove();
			}else if(Method_Type == 'wx_hongbao' || Method_Type == 'wx_zhuanzhang'){
				$(this).parent().parent().siblings(".method_name_rows").remove();
			}
			
		});
	},
	
	property_edit_init:function(){
	
		$('#property_edit_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
		});
		
	},
	
	property_add_init:function(){
	   
	   $("#products .font_btn").click(function(){
			
			var TypeID = $("#Type_ID").val();
			var ProductsID = $("#ProductsID").val();
			
			if(TypeID.length > 0){
				$.ajax({
					type	: "POST",
					url		: "ajax.php",
					data	: "action=get_properity&UsersID="+$("#UsersID").val()+"&TypeID="+$("#Type_ID").val()+"&ProductsID="+$("#ProductsID").val(),
					dataType: "json",
					async : false,
					success	: function(data){
						if(data.msg){
							$("#propertys").css("display","block");
							$("#propertys").html(data.msg);
						}else{
							alert("???????????????");
						}
					}
				});	
			} else {				
			   alert("?????????????????????");
			   $("#Category").focus();				
			}
		});
		
		$("#products .font_btn_clear").click(function(){
		    $("#propertys").css("display","none");
			$("#propertys").html("");
		});
		
		
		$('#property_add_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
		});
	
	},
	products_form_init:function(){
		$("input[name='Products_IsShippingFree']").click(function(){
			var is_shipping_free = $(this).attr('value');
			
			if(is_shipping_free == 1){
				$('#free_shipping_company').css('display','block');
			}else{
				$('#free_shipping_company').css('display','none');
			}
		});
		
		$('a[href=#select_category]').each(function(){
			$(this).click(function(){
				$('#select_category').leanModal();
			});
		});
		
		var category = function(object1){
			var c_k = true;
			object1.parent().find("input").each(function(){
				if(!$(this).attr("checked")){
					c_k = false;
				}
			});
			if(c_k){
				object1.parent().prev("dt").find("input").attr("checked",true);
			}else{
				object1.parent().prev("dt").find("input").removeAttr("checked"); 
			}
		};
		
		$("#select_category .catlist input:checkbox").click(function(){
			var flag = $(this).attr("rel");
			if(flag == 1){
				if($(this).is(':checked')){
					$(this).parent().next("dd").find("input").attr("checked",true);
				}else{
					$(this).parent().next("dd").find("input").attr("checked",false);
				}
			}else{
				category($(this).parent());
			}
		});
		
		$('input[name=FinanceType]').click(function(){
			var $val = $(this).val();
			var TypeID = $("#products #Type_ID").find("option:selected").attr('value');
			if(TypeID.length > 0){
				$.ajax({
					type	: "POST",
					url		: "ajax.php",
					data	: "action=get_attr&UsersID="+$("#UsersID").val()+"&TypeID="+$("#Type_ID").val()+"&ProductsID="+$("#ProductsID").val()+"&FinanceType="+$('input[name=FinanceType]:checked').val(),
					dataType: "json",
					async : false,
					success	: function(data){
						
						if(data.content){
							$("#attrs").css("display","block");
							$("#attrs").html(data.content);
						}else{
							alert("???????????????");
						}
					}
				});	
			}
		});
		
		$("#products #Type_ID").change(function(){
			
			var TypeID = $("#Type_ID").val();
			var ProductsID = 0;
			
			if(TypeID.length > 0){
				$.ajax({
					type	: "POST",
					url		: "ajax.php",
					data	: "action=get_attr&UsersID="+$("#UsersID").val()+"&TypeID="+$("#Type_ID").val()+"&ProductsID="+$("#ProductsID").val()+"&FinanceType="+$('input[name=FinanceType]:checked').val(),
					dataType: "json",
					async : false,
					success	: function(data){
						
						if(data.content){
							$("#attrs").css("display","block");
							$("#attrs").html(data.content);
						}else{
							alert("???????????????");
						}
					}
				});	
			} else {
				
			 $("#attrs").html('');
			   $("#Category").focus();
				
			}
		});
		
	},
	products_add_init:function(){
		
		$("#product_add_form").submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
		});

		shop_obj.products_form_init();
	},
	
	products_edit_init:function(){
		
		$("#product_edit_form").submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
		});
		
		shop_obj.products_form_init();
	},
	
	products_list_init:function(){
		$('a[href=#search]').click(function(){
			$('form.search').slideDown();
			return false;
		});
		$(".mousehand").each(function(){
            $(this).click(function(){
            	global_obj.create_layer('??????????????????', '/member/distribute/levelview.php?ProductsID='+$(this).attr('id'),971,500,0,0);
            })
        })
	},
	
	products_category_init:function(){
		global_obj.file_upload($('#HomeFileUpload'), $('#shop_category_form input[name=ImgPath]'), $('#look'));
		$('#products .category .m_lefter dl').dragsort({
			dragSelector:'dd',
			dragEnd:function(){
				var data=$(this).parent().children('dd').map(function(){
					return $(this).attr('CateId');
				}).get();
				$.get('?m=shop&a=products', {do_action:'shop.products_category_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'ul, a',
			placeHolderTemplate:'<dd class="placeHolder"></dd>',
			scrollSpeed:5
		});
		
		$('#products .category .m_lefter ul').dragsort({
			dragSelector:'li',
			dragEnd:function(){
				var data=$(this).parent().children('li').map(function(){
					return $(this).attr('CateId');
				}).get();
				$.get('?m=shop&a=products', {do_action:'shop.products_category_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'a',
			placeHolderTemplate:'<li class="placeHolder"></li>',
			scrollSpeed:5
		});
		
		$('#products .category .m_lefter ul li').hover(function(){
			$(this).children('.opt').show();
		}, function(){
			$(this).children('.opt').hide();
		});
		
		$('#pro-list-type .item').removeClass('item_on').each(function(){
			$(this).click(function(){
				$('#pro-list-type .item').removeClass('item_on');
				$(this).addClass('item_on');
				$('#shop_category_form input[name=ListTypeId]').val($(this).attr('ListTypeId'));
			});
		}).filter('[ListTypeId='+$('#shop_category_form input[name=ListTypeId]').val()+']').addClass('item_on');
		
		$('#shop_category_form').submit(function(){return false;});
		$('#shop_category_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#shop_category_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=shop&a=products&d=category';
				}else{
					alert(data.msg);
					$('#shop_category_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	},
	products_attr_add_init:function(){
		shop_obj.products_attr_cu();
		$('#shop_attr_add_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
		});
	},
	products_attr_edit_init:function(){
		shop_obj.products_attr_cu();
		$('#shop_attr_edit_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
		});
	
	},
	products_attr_cu:function(){
		//????????????create ???  update ????????????
		
		//?????????????????????????????????????????????????????????????????????textarea
		$(".Attr_Input_Type").click(function(){
			var value  = parseInt($(this).val());
			$("#Attr_Values").removeAttr('style');
			if(value == 0||value == 2){
				
				$("#Attr_Values").attr({"disabled":true});
			}else{
				$("#Attr_Values").removeAttr('disabled');
			   
		
			}
			
			//????????????????????????????????????????????????
			if(value == 0||value == 2){
				 $("#Attr_Values").removeAttr('notnull');
			}else{
				$("#Attr_Values").attr({"notnull":true});
			}
			
		
		});
		
		//???????????????????????????
		$("#Type_ID").change(function(){
			var Type_ID_Opt_Item = $(this).parent();
			var Type_ID  =  $(this).val();
			if(Type_ID.length >0 ){
				$('#attr_group_opt').remove();	
				$.get(base_url+'member/shop/ajax.php',{Type_ID:Type_ID,action:'get_attr_group'},function(data){
					
					if(data.status == 1){
						var select = document.createElement("select");
						$(data.content).each(function(i){
							select.options[i] = new Option(this, i);
						});
						
						var select_dom = $(select);
						select_dom.attr("name","Attr_Group");
						var opt_item = $(document.createElement("div"));
						opt_item.attr({class:"opt_item",id:"attr_group_opt"});
					    
						var span_input = $('<span class="input"></span>');
						span_input.append(select_dom);
					    
						opt_item.append($("<label>?????????:</label>"));
						opt_item.append(span_input);
						
						Type_ID_Opt_Item.after(opt_item);
						
						var clear_div = $('<div class="clear"></div>');
						Type_ID_Opt_Item.after(clear_div);
						//testDiv.appendChild(select); 					
					}
					
				},'json');
			}
			
		});
		
	},
	products_property_init:function(){
		var ul=$('#products_property_form ul');
		var add_btn=ul.find('img[src*=add]');
		var add_fun=function(){
			add_btn.click(function(){
				ul.append(ul.children('li:last').clone(true));
				ul.children('li').eq(-2).children('img[src*=add]').remove();
				ul.find('li:last input').val('');
			});
		};
		add_fun();
		ul.find('img[src*=del]').click(function(){
			if(ul.children('li').size()>1){
				$(this).parent().remove();
				
				if(ul.find('img[src*=add]').size()==0){
					ul.children('li:last').append(add_btn);
					add_fun();
				}
			}
		});
		
		$('#products .property .m_lefter ul li').hover(function(){
			$(this).children('.opt').show();
		}, function(){
			$(this).children('.opt').hide();
		});
		
		$('#products_property_form').submit(function(){return false;});
		$('#products_property_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#products_property_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=shop&a=products&d=property';
				}else{
					alert(data.msg);
					$('#products_property_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	},
	
	orders_init:function(){
		$('#search_form input:button').click(function(){
			window.location='./?'+$('#search_form').serialize()+'&do_action=shop.orders_export';
		});
		
		$("#search_form .output_btn").click(function(){
			window.location='./output.php?'+$('#search_form').serialize()+'&type=order_detail_list';
		});
		
		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});
		
		$('#orders .cp_title #cp_view, #orders .cp_title #cp_mod').click(function(){
			$('#orders .cp_title div').removeClass('cur');
			$(this).addClass('cur');
			
			if($(this).attr('id')=='cp_view'){
				$('#orders_mod_form .cp_item_view').show();
				$('#orders_mod_form .cp_item_mod').hide();
			}else{
				$('#orders_mod_form .cp_item_view').hide();
				$('#orders_mod_form .cp_item_mod').show();
			}
		});
		$('#orders_mod_form').submit(function(){$('#orders_mod_form input:submit').attr('disabled', true);});
		$('#orders_mod_form .cp_item_mod .back').click(function(){$('#orders .cp_title #cp_view').click();});
		
		var change_is_read=function(){
			$('#order_list tr[IsRead=0]').off().click(function(){
				var o=$(this);
				$.get('?', 'do_action=shop.orders_set_read&OrderId='+o.attr('OrderId'), function(data){
					if(data.ret==1){
						o.removeClass('is_not_read').off();
					}
				}, 'json');
			});
		};
		
		var refer_time=60;
		var refer_left_time=0;
		var refer_ing=false;
		var auto_refer=function(){
			if($('#auto_refer').is(':checked')){
				if(refer_left_time<refer_time){
					$('#search_form div label').html('<span><strong>'+(refer_time-refer_left_time)+'</strong></span>??????????????????');
					refer_left_time++;
				}else if(refer_ing==false){
					refer_ing=true;
					$('#search_form div label').html('???????????????..');
					
					$.get('?', 'do_action=shop.orders_is_not_read', function(data){
						refer_ing=false;
						refer_left_time=0;
						if(data.ret==1){
							var have_new_order=false;
							var html='';
							for(var i=0; i<data.msg.length; i++){
								if($('#order_list tr[OrderId='+data.msg[i]['OrderId']+']').size()==0){	//????????????????????????
									have_new_order=true;
									html+='<tr class="is_not_read" IsRead="0" OrderId="'+data.msg[i]['OrderId']+'">';
										html+='<td nowrap="nowrap">?????????</td>';
										html+='<td nowrap="nowrap">'+data.msg[i]['OId']+'</td>';
										html+='<td>'+data.msg[i]['Name']+'</td>';
										html+='<td nowrap="nowrap">???'+data.msg[i]['Price']+'</td>';
										NeedShipping && (html+='<td nowrap="nowrap">'+data.msg[i]['Shipping']+'</td>');
										html+='<td nowrap="nowrap">'+orders_status[data.msg[i]['OrderStatus']]+'</td>';
										html+='<td nowrap="nowrap">???'+data.msg[i]['OrderTime']+'</td>';
										html+='<td nowrap="nowrap" class="last"><a href="?m=shop&a=orders&d=view&OrderId='+data.msg[i]['OrderId']+'"><img src="'+domain.static+'/member/images/ico/view.gif" align="absmiddle" alt="??????" /></a><a href="?m=shop&a=orders&do_action=shop.orders_del&OrderId='+data.msg[i]['OrderId']+'" title="??????" onClick="if(!confirm(\'????????????????????????????????????\')){return false};"><img src="'+domain.static+'/member/images/ico/del.gif" align="absmiddle" /></a></td>';
									html+='</tr>';
								}
							}
							if(have_new_order){
								$('#search_form div label').html('<span>??????????????????</span>');
								$('#order_list tbody').prepend(html);
								change_is_read();
								$('body').prepend('<bgsound src="'+domain.static+'/member/images/shop/tips.mp3" autostart="true" loop="1">');
							}else{
								$('#search_form div label').html('<span>??????????????????</span>');
							}
						}else{
							$('#search_form div label').html('<span>??????????????????</span>');
						}
					}, 'json');
				}
			}else{
				$('#search_form div label').html('??????????????????');
				refer_left_time=0;
				refer_ing=false;
			}
			setTimeout(auto_refer, 1000);
		};
		auto_refer();
		change_is_read();
		
		$('#orders a[name=print]').click(function(){
			var pos_l=($(document.body).width()-670)/2;
			var pos_t=($(window).height()-500)/2;
			$('#print_cont').css({'top':pos_t+'px','left':pos_l+'px'}).fadeIn();
			var OrderId=$(this).parent().parent().attr('OrderId');
			$('#get_data').attr('src','./?m=shop&a=print&OrderId='+OrderId+'&n='+Math.random());
		});
		
		$('#order_list a.send_print').click(function(){
			var orderid = $(this).attr("ret");
			$('#select_template #linkid').attr("value",orderid);
			$('#select_template').leanModal();
		});
		
		$('#submit_form #checkall').click(function(){
			if($(this).is(":checked")){
				$("#submit_form input[name=OrderID\\[\\]]").attr("checked","true");
			}else{
				$("#submit_form input[name=OrderID\\[\\]]").removeAttr("checked");
			}			
		});
		
		$("#submit_form input[name=OrderID\\[\\]]").click(function(){
			if($(this).is(":checked")){
				var i = 1;
				$("#submit_form input[name=OrderID\\[\\]]").each(function(index, element) {
                    if(!$(this).is(":checked")){
						i=0;
					}
                });
				if(i==1){
					$("#submit_form #checkall").attr("checked","true");
				}
			}else{
				$("#submit_form #checkall").removeAttr("checked");
			}
		});
		
		$("#submit_form label").click(function(){
			var j = 0;
			$("#submit_form input[name=OrderID\\[\\]]").each(function(index, element) {
                if($(this).is(":checked")){
					j=1;
				}
            });
			if(j==1){
				$('#select_template #linkid').attr("value",0);
				$('#select_template').leanModal();
			}else{
				alert("???????????????");
				return false;
			}
			
		});
		
		$("#select_template label").click(function(){
			var templateid = $("#select_template #templates").val();
			if(templateid==0 || templateid == ""){
				alert("?????????????????????");
				return false;
			}
			
			var linkid = $('#select_template #linkid').attr("value");
			if(linkid==0){
				$("#submit_form input[name=templateid]").attr("value",templateid);				
				$("#select_template,#lean_overlay").hide();
				window.open('send_print.php?'+$('#submit_form').serialize());
			}else{
				$("#select_template,#lean_overlay").hide();
				window.open('send_print.php?templateid='+templateid+'&OrderID='+linkid);
			}
			
		});
	},
	
	print_orders_init:function(){
		
		$('.r_nav, .ui-nav-tabs').hide();
		$('html,body').css('background','none');
		$('.iframe_content').removeClass('iframe_content');	
		$('.print_area input[name=print_close]').click(function(){
			$('#print_cont').fadeOut();
		});
		
		$('.print_area input[name=print_go]').click(function(){
			window.print();
		});
		$('.print_area input[name=print_close]').click(function(){
			$(window.parent.document).find('#print_cont').fadeOut();
		});
		
	},
	 distribute_config_init:function(){		 
		
		$('#level_form').submit(function(){
		
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#level_form input:submit').attr('disabled', true);
			return true;
		});
		
		//??????????????????
		$("#add_distribute").click(function(){
		  var li_item = '<li class=\"item\"><input name=\"distribute_from[]\" value=\"\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\"> ??? <input name=\"distribute_to[]\" value=\"\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\"> (???) &nbsp;&nbsp; <input name=\"distribute_rate[]\" value=\"\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\"> <span>%</span> <a><img src=\"/static/member/images/ico/del.gif\" hspace=\"5\"></a></li>';
			$("ul#distribute_panel").append(li_item);

		});

		$("#distribute_panel li.item a").live('click',function(){
				$(this).parent().remove();
		});
	},
	
	confirm_form_init:function(){
		
		$("#add_man").click(function(){
			var li_item = '<li class="item">????????? <input name="man_reach[]" value="" class="form_input" size="10" maxlength="10" type="text"> ????????? <input name="man_award[]" value="" class="form_input" size="10" maxlength="10" type="text"> <a><img src="/static/member/images/ico/del.gif" hspace="5"></a></li>';
			$("ul#man_panel").append(li_item);
		});
		
		$("#man_panel li.item a").live('click',function(){
				$(this).parent().remove();
		});
		
		$("#add_integral_law").click(function(){
			var li_item = '<li class="item">????????? <input name="Integral_Man[]" value="" class="form_input" size="10" maxlength="10" type="text"> ??????<input name="Integral_Use[]" value="" class="form_input" size="10" maxlength="10" type="text">???<a><img src="/static/member/images/ico/del.gif" hspace="5"></a></li>';
			$("ul#integral_panel").append(li_item);
		});
		
		$("#integral_panel li.item a").live('click',function(){
				$(this).parent().remove();
		});

		//??????????????????
		$("#add_distribute").click(function(){
		  var li_item = '<li class=\"item\"><input name=\"distribute_from[]\" value=\"\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\"> ??? <input name=\"distribute_to[]\" value=\"\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\"> (???) &nbsp;&nbsp; <input name=\"distribute_rate[]\" value=\"\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\"> <span>%</span> <a><img src=\"/static/member/images/ico/del.gif\" hspace=\"5\"></a></li>';
			$("ul#distribute_panel").append(li_item);

		});

		$("#distribute_panel li.item a").live('click',function(){
				$(this).parent().remove();
		});
		
		
	},
	dis_title_init:function(){
		
		$('#level_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#level_form input:submit').attr('disabled', true);
			return true;
		});
		
		$(".title_val").live('blur',function(){
			
		});
		
		$("#add_dis_title").click(function(){
			var count = $("#dis_pro_title_table tr").length;
			var dis_title_tr = shop_obj.dis_title_tr.replace(/tr_index/,count);
	
			if(count <= 4){
				addTr('dis_pro_title_table', -1,dis_title_tr);
			}
		});
	
		$(".input_del").live('click',function(){
			$(this).parent().parent().remove();
		});
		
		$("#clear_form").click(function(){
			    /*?????????form??????*/
		        
				var  param = {'action':'clear_dis_level','UsersID':Users_ID};
				
				$.post(base_url+'member/shop/ajax.php',param,function(data){
					if(data.status == 1){
						$(':input','#level_form')  
						.not(':button, :submit, :reset, :hidden')  
						.val('')  
						.removeAttr('checked')  
						.removeAttr('selected'); 
						
						alert(data.msg);
					}
					   
				},'json');
				
				
		});
	},
	distribute_init:function(){
		
		$('.upd_select').dblclick(function(){
			var o=$(this).children('.upd_txt');
			if(o.children('select').size()){return false;}
			
			var s_html='<select>';
			for(i=0;i<level_ary.length;i++){
				var selected=o.html()==level_ary[i]?'selected':'';
				s_html+='<option value="'+i+'" '+selected+'>'+level_ary[i]+'</option>';
			}
			s_html+='</select>';
			o.data('text', o.html()).html(s_html);
			o.children('select').focus();
			
			o.children('select').bind('change blur', function(){
				var value=parseInt($(this).val());
				if(value>=level_ary.length){
					value=0;
				}
				
				if(level_ary[value]==o.data('text')){
					o.html(o.data('text'));
					return false;
				}
				$('#update_post_tips').html('???????????????...').css({left:$(window).width()/2-100}).show();
				
				$.post('/member/shop/distributes.php?', "action=protitle&AccountID="+o.parent().parent().attr('AccountID')+'&Value='+value, function(data){
					if(data.status==1){
						var msg='???????????????';
						o.html(level_ary[value]);
					}else if(data.msg!=''){
						var msg=data.msg;
						o.html(o.data('text'));
					}else{
						var msg='????????????????????????????????????';
						o.html(o.data('text'));
					}
					$('#update_post_tips').html(msg).fadeOut(3000);
				}, 'json');
			});
		});
        //???????????????????????????
		$(".agent_info").click(function(){
			
			var account_id = $(this).attr('agent-id');
			$("#agent-info-modal").modal('show');
			var param = {account_id:account_id,action:'get_dis_agent_form'};
			$.get(base_url+'member/shop/ajax.php',param,function(data){
				if(data.status == 1){
					$("#agent-info-modal").find('div.modal-body').html(data.content);	
					 //??????????????????
					$("img.trigger").click(function() {
					$('div.ecity ').removeClass('showCityPop');
					$(this).parent().parent().addClass('showCityPop');
					});

					//??????????????????
					$("input.close_button").click(function() {
						$(this).parent().parent().parent().removeClass('showCityPop');
					});
		
				}
			},'json');
		     //$("#province_dialog").removeClass("hidden");
		});
		
		//??????????????????
		$(".J_Group").live('click',function(){
			var province_ids = $(this).attr('value');
			var checked = $(this).prop('checked');
			
			if(checked){
				province_ids.split(',').each(function(province_id){
			    	if(!$("#J_Province_"+province_id).prop('disabled')){
						$("#J_Province_"+province_id).prop('checked',true);
					}
				});
			}else{
					
				province_ids.split(',').each(function(province_id){
					if(!$("#J_Province_"+province_id).prop('disabled')){
						$("#J_Province_"+province_id).prop('checked',false);
					}
				});
			}
			
		});
		
		$(".zhuanzhang").click(function(){			
			var userid = $(this).attr('userid');
			$('#userid').attr("value",userid);
			$("#zhuanzhang-info-modal").modal('show');
		});
		
		$("#confirm_zhuanzhang_btn").click(function(){
			if($("#amount").attr("value")==''){
				alert('?????????????????????');
				return false;
			}
			var amount = parseFloat($("#amount").attr("value"));
			if(amount<=0){
				alert('?????????????????????');
				return false;
			}
			var userid = $('#userid').attr("value");
			if(userid<=0){
				alert('??????????????????');
				return false;
			}
			$("#zhuanzhang-info-modal").modal('hide');
			window.location.href="?action=zhuanzhang&userid="+userid+'&amount='+amount;
		});
       
		
		$("#confirm_dis_area_agent_btn").click(function(){
			var JProvinces = $("#dis_agent_form input[name='J_Province']").fieldValue(); 
		    var KCitys = $("#dis_agent_form input[name='K_City']").fieldValue();
			var account_id = $("#account_id").attr('value');
	
			var param = {JProvinces:JProvinces,
						  KCitys:KCitys,	
			             account_id:account_id,
						 action:'save_dis_agent_area'};
			
			$.post(base_url+'member/shop/ajax.php',param,function(data){
				if(data.status == 1){
					$("#agent-info-modal").modal('hide');	
				}
			},'json');
			
		});
		
		//???????????????????????????
		$("a.reject_btn").click(function(){
			$('#reject_withdraw form input[name=Bank_Card]').val('');
			$('#reject_withdraw form input[name=Record_ID]').val($(this).parent().parent().attr('Record_ID'));
			$('#reject_withdraw form').show();
			$('#reject_withdraw .tips').hide();
			$('#reject_withdraw').leanModal();
		});

		//????????????????????????
		$('#reject_withdraw form').submit(function(){return false;});
		$('#reject_withdraw form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};

			$(this).attr('disabled', true);
			$.post('/member/shop/ajax.php?', $('#reject_withdraw form').serialize(), function(data){
				$('#reject_withdraw form input:submit').attr('disabled', false);
			
				if(data.status == 1){
					$('#reject_withdraw .tips').html('??????????????????????????????').show();
				}else{
					$('#reject_withdraw .tips').html('???????????????????????????????????????????????????').show();
				};
				
				$('#reject_withdraw form').hide();
				$('#reject_withdraw').leanModal();
			}, 'json');
		});	


		$('a.mod-card').click(function(){
			$('#Bank_Card').inputFormat('account');	
			$('#Bank_Card').inputFormat('account');	
			$('#mod_account_card .h span').html(' ('+$(this).parent().parent().children('td[field=1]').html()+')');
			$('#mod_account_card form input[name=Bank_Card]').val('');
			$('#mod_account_card form input[name=UserID]').val($(this).parent().parent().attr('UserID'));
			$('#mod_account_card form').show();
			$('#mod_account_card .tips').hide();
			$('#mod_account_card').leanModal();
		});

		$('#mod_account_card form').submit(function(){return false;});
		
		$('#mod_account_card form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('/member/shop/ajax.php?', $('#mod_account_card form').serialize(), function(data){
				$('#mod_account_card form input:submit').attr('disabled', false);
				
				if(data.status == 1){
					$('#mod_account_card .tips').html('???????????????????????????').show();
				}else{
					$('#mod_account_card .tips').html('????????????????????????????????????????????????').show();
				};
				
				$('#mod_account_card form').hide();
				$('#mod_account_card').leanModal();
			}, 'json');
		});	

		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});	


	},
	
	dis_config_init:function(){
		//??????????????????????????????????????????????????????
		
		$('.Bonus_Limit_Enable').live('click',function(){
			var level = $(this).attr('level')
			
		    if($(this).prop('checked')){
				$('.Bonus_Limit_Enable').each(function(){
					var my_level = $(this).attr('level');
					if(my_level < level){
						$(this).prop('checked',true);
					}
				});
			}
			
		});
		
		 $("#Dis_Self_Bonus").click(function(){
			 
			var url = base_url+'member/shop/ajax.php';
			var level  =$("#Dis_Level").val();

			var param = {action:'get_dis_bonus_trs', 
			 			level:level,
						self:$(this).prop('checked')};
			$.get(url,param,function(data){
			  	if(data.status == 1){
			  		$('#dis_bonus_trs').html(data.trs);
					$("#Dis_Mobile_Level").html(data.dropdown);
				}
			},'json');
			
			
		 });			 
		
		/*???????????????????????????????????????*/
	    $("#Dis_Level").change(function(){
		
			var url = base_url+'member/shop/ajax.php';
			var level  =$(this).val();
			var self = $('#Dis_Self_Bonus').prop('checked');
		
			var param = {action:'get_dis_bonus_trs', 
			 			level:level,
						self:self};
			
			$.get(url,param,function(data){
				
				if(data.status == 1){
			  		$('#dis_bonus_trs').html(data.trs);
					$("#Dis_Mobile_Level").html(data.dropdown);
				}
			},'json');
		});
		
		$('#type').change(function(){
			for(var i=1; i<=4; i++){
				$('#rows_'+i).hide();
			}
			$('#rows_'+this.value).show();
		});
		
		//??????????????????
		$("input[name=Fanwei]").click(function(){
			if($(this).attr("value") == 0){
				$(this).parent().children(".products_option").hide();
			}else{
				$(this).parent().children(".products_option").show();
			}
		});
		
		$("input[name=DFanwei]").click(function(){
			if($(this).attr("value") == 0){
				$(this).parent().children(".products_option").hide();
			}else{
				$(this).parent().children(".products_option").show();
			}
		});
		
		$(".products_option .search_div .button_search").click(function(){
			var object = $(this).parent();
			var catid = object.children("select").val()
			var keyword = object.children("input").val();
			
			var param = {cate_id:catid,keyword:keyword,action:'get_product'};
			$.get('?',param,function(data){
				object.parent().children(".select_items").children(".select_product0").html(data);
			});
		});
		
		$(".products_option .select_items .button_add").click(function(){
			var text = $(this).parent().children(".select_product0").find("option:selected").text();
			var value = $(this).parent().children(".select_product0").find("option:selected").val();
			if($(this).parent().children(".select_product1").find("option:contains("+text+")").length == 0 && typeof(value)!='undefined'){
				$(this).parent().children(".select_product1").append("<option value='"+value+"'>"+text+"</option>");
			}
			
			var strids = $(this).parent().children("input").val();
			if(typeof(value)!='undefined'){
				if(strids == ''){
					$(this).parent().children("input").val(','+value+',');
				}else{
					strids = strids.replace(','+value+',',",");
					$(this).parent().children("input").val(strids+value+',');
				}
			}
		});
		
		$(".products_option .options_buttons .button_remove").click(function(){//????????????		
			var select_obj = $(this).parent().parent().children(".select_items").children(".select_product1").find("option:selected");
			var input_obj = $(this).parent().parent().children(".select_items").children("input");
			var strids = input_obj.val();
			select_obj.each(function(){
				$(this).remove();
				strids = strids.replace(','+$(this).val()+',',",");
			});
			if(strids==','){
				strids = '';
			}
			input_obj.val(strids);
		});
		
		$(".products_option .options_buttons .button_empty").click(function(){//????????????
			 $(this).parent().parent().children(".select_items").children(".select_product1").empty();
			 $(this).parent().parent().children(".select_items").children("input").val('');
		});
		
		//??????????????????end
		//yongjinrenshuxianzhi
		
		$("input[name=Balance_Limit_Enabled]").click(function(){
			if($(this).attr("value") == 0){
				$(this).parent().children("div").hide();
			}else{
				$(this).parent().children("div").show();
				$('#fanben_limit').show();
			}
		});
		//????????????	
		$("input[name=Fanben]").click(function(){
			if($(this).attr("value") == 0){
				$(this).parent().children("div").hide();
			}else{
				$(this).parent().children("div").show();
				$('#fanben_limit').show();
			}
		});
		
		$('#ftype').change(function(){
			if(this.value==0){
				$('#frows_1').hide();
			}else{
				$('#frows_1').show();
			}
		});
		
		//????????????		
		$("input[name=Fuxiao]").click(function(){
			if($(this).attr("value") == 0){
				$(this).parent().children("div").hide();
			}else{
				$(this).parent().children("div").show();
			}
		});
		
		
		$('#dtype').change(function(){
			for(var i=2; i<=3; i++){
				$('#drows_'+i).hide();
			}
			$('#drows_'+this.value).show();
		});
		
		$("#dsearch").click(function(){
			var param = {cate_id:$("#DCategory").val(),keyword:$("#dkeyword").val(),action:'get_product'};
			$.get('?',param,function(data){
				$("#dselect_product").html(data);
			});
		});
		
		$("#dselect_product").change(function(){
			$("#dlimit2").attr("value",$(this).val());
			$("#dproducts_name").attr("value",$(this).find("option:selected").text().split('---')[0]);
		});
		
		$('.menu_add').click(function(){
			var count_num = $('#menubox tbody tr').size();
			$('#for_menu input[name=MLink\\[\\]]').attr('id','distribute_url_'+count_num);
			$('#for_menu .btn_select_url').attr('ret',count_num);
			$('#menubox').append($('#for_menu').html()).end();
			$('#menubox').find('.items_del').click(function(){
				$(this).parent().parent().remove();  
			});
		});
		
		$('#menubox').on('click','.btn_select_url',function(){
			
			global_obj.create_layer('????????????', '/member/material/sysurl.php?dialog=1&input=distribute_url_'+$(this).attr("ret"),1000,500);
		});
		
		$("input[name=Dis_Agent_Type]").click(function(){
			var region_aget = "???%<input name=\"Agent_Rate[Province]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\"> ???%<input name=\"Agent_Rate[City]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\">";
			var common_aget = "%<input name=\"Agent_Rate\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull=\"\" type=\"text\">";
			var type = $(this).attr('value');
			if(type == 0){
				$("#Agent_Rate_Input").html('');
			}else if(type == 1){
				$("#Agent_Rate_Input").html(common_aget);
			}else if(type == 2){
				$("#Agent_Rate_Input").html(region_aget);
			
			}
		});
		
		
		 $('#distribute_config_form input:submit').click(function(){
			if(global_obj.check_form($('#distribute_config_form *[notnull]'))){
				return false;
			};
			
		});
		 
	},
	dis_title_tr:"<tr fieldtype=\"text\"><td>tr_index</td><td><input class=\"form_input\" value=\"\" name=\"Dis_Pro_Title[Name][]\" notnull=\"\" type=\"text\"></td><td><input class=\"form_input title_val\" value=\"0\" name=\"Dis_Pro_Title[Saleroom][]\"  type=\"text\"></td><td><input class=\"form_input\" value=\"0\" name=\"Dis_Pro_Title[Bonus][]\" type=\"text\"></td><td><a href=\"javascript:void(0);\" class=\"input_add\"><img src=\"/static/member/images/ico/del.gif\"></a></td></tr>",
	agent_province_check:function(obj,type){
		 
		 if (type == 'Province') {
			 
            var checked = $(obj).prop('checked');
            var province_id = $(obj).attr('value');

        }
	},
	
	recieve_init:function(){
		$('#recieve_edit input:submit').click(function(){			
			if(global_obj.check_form($('#recieve_edit *[notnull]'))){
				return false;
			};			
		});
	},
	
	backorder_edit:function(){
		$("#reject_btn").click(function(){
			$("#btns").hide();
			$("#reject").show();
		});
		
		$("#goback_reject").click(function(){
			$("#reject").hide();
			$("#btns").show();
		});
		
		$('#reject_form').submit(function(){
			if(global_obj.check_form($('#reject_form *[notnull]'))){return false};
			$('#reject_form input:submit').attr('disabled', true);
			return true;
		});
		
		$("#recieve_btn").click(function(){
			$("#btns").hide();
			$("#recieve").show();
		});
		
		$("#goback_recieve").click(function(){
			$("#recieve").hide();
			$("#btns").show();
		});
		
		$('#recieve_form').submit(function(){
			if(global_obj.check_form($('#recieve_form *[notnull]'))){return false};
			$('#recieve_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	orders_send:function(){
		$('#order_send_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#order_send_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	
	
	//---------- diy?????? ----------//
	diy_init:function(){
		shop_obj.firstrproperty = $(".ps2").html(); //???????????????
		$(".square_sprite").each(function(index, element) {
			$(element).dragObj({	
				"isClone" 	: true,	
				"hitPoint"	: {	
					"hitType"	: true,	
					"hitObj"	: $(".ipad")	
				}	
			}); 	
		});
		$(".ipad .sprite1").each(function(index, element) {
			var packagename = $(element).attr("packagename");
			$(element).find(".dragPart").orderDrag({"package":packagename});  
        });
		//------ ????????????????????? ------//
		
		//------ ?????????????????????p1?????? -------//
		global_obj.file_upload($("#upfile_p1_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .imgObj").eq(0).html('<img src="'+filepath+'" width="146" />');
			}
		});
		global_obj.file_upload($("#upfile_p1_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .imgObj").eq(1).html('<img src="'+filepath+'" width="146" />');
			}
		});
		
		//------ ?????????????????????p0?????? -------//
		global_obj.file_upload($("#upfile_p0_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p0ImgFrame").eq(0).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p0_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p0ImgFrame").eq(1).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p0_2"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p0ImgFrame").eq(2).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		
		//------ ?????????????????????p6?????? -------//
		global_obj.file_upload($("#upfile_p6_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p6ImgFrame").eq(0).find(".imgObj").html('<img src="'+filepath+'" width="160" />');
			}
		});
		global_obj.file_upload($("#upfile_p6_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p6ImgFrame").eq(1).find(".imgObj").html('<img src="'+filepath+'" width="160" />');
			}
		});
		global_obj.file_upload($("#upfile_p6_2"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p6ImgFrame").eq(2).find(".imgObj").html('<img src="'+filepath+'" width="160" />');
			}
		});
		global_obj.file_upload($("#upfile_p6_3"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p6ImgFrame").eq(3).find(".imgObj").html('<img src="'+filepath+'" width="160" />');
			}
		});
		
		//------ ?????????????????????p6?????? -------//
		global_obj.file_upload($("#upfile_p7_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p7ImgFrame").eq(0).find(".imgObj").html('<img src="'+filepath+'" width="128" />');
			}
		});
		global_obj.file_upload($("#upfile_p7_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p7ImgFrame").eq(1).find(".imgObj").html('<img src="'+filepath+'" width="128" />');
			}
		});
		global_obj.file_upload($("#upfile_p7_2"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p7ImgFrame").eq(2).find(".imgObj").html('<img src="'+filepath+'" width="128" />');
			}
		});
		global_obj.file_upload($("#upfile_p7_3"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p7ImgFrame").eq(3).find(".imgObj").html('<img src="'+filepath+'" width="128" />');
			}
		});
		global_obj.file_upload($("#upfile_p7_4"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p7ImgFrame").eq(4).find(".imgObj").html('<img src="'+filepath+'" width="128" />');
			}
		});
		
		//------ ?????????????????????p8?????? -------//
		global_obj.file_upload($("#upfile_p8_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p8ImgFrame").eq(0).find(".imgObj").html('<img src="'+filepath+'" width="146" />');
			}
		});
		global_obj.file_upload($("#upfile_p8_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p8ImgFrame").eq(1).find(".imgObj").html('<img src="'+filepath+'" width="146" />');
			}
		});
		global_obj.file_upload($("#upfile_p8_2"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p8ImgFrame").eq(2).find(".imgObj").html('<img src="'+filepath+'" width="146" />');
			}
		});
		
		//------ ?????????????????????p9?????? -------//
		global_obj.file_upload($("#upfile_p9_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p9ImgFrame").eq(0).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p9_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p9ImgFrame").eq(1).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p9_2"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p9ImgFrame").eq(2).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p9_3"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p9ImgFrame").eq(3).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p9_4"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p9ImgFrame").eq(4).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		
		//------ ?????????????????????p10?????? -------//
		global_obj.file_upload($("#upfile_p10_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p10ImgFrame").eq(0).find(".imgObj").html('<img src="'+filepath+'" width="146" />');
			}
		});
		global_obj.file_upload($("#upfile_p10_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p10ImgFrame").eq(1).find(".imgObj").html('<img src="'+filepath+'" width="146" />');
			}
		});
		global_obj.file_upload($("#upfile_p10_2"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p10ImgFrame").eq(2).find(".imgObj").html('<img src="'+filepath+'" width="146" />');
			}
		});
		
		//------ ?????????????????????p11?????? -------//
		global_obj.file_upload($("#upfile_p11_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p11ImgFrame").eq(0).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p11_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p11ImgFrame").eq(1).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p11_2"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p11ImgFrame").eq(2).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p11_3"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p11ImgFrame").eq(3).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p11_4"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p11ImgFrame").eq(4).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		
		//------ ???????????????p3?????? -------//
		global_obj.file_upload($("#upfile"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .imgObj").html('<img src="'+filepath+'" width="292" />');
			}
		});
		
		//------ ??????????????????p4?????? -------//
		global_obj.file_upload($("#upfile_p4_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p4ImgFrame img").eq(0).attr("src",filepath).show().siblings().hide();
				$("#p4LookDetail0").html('<img src="'+filepath+'" width="50" />');
			}
		});
		global_obj.file_upload($("#upfile_p4_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p4ImgFrame img").eq(1).attr("src",filepath);
				$("#p4LookDetail1").html('<img src="'+filepath+'" width="50" />');
			}
		});
		global_obj.file_upload($("#upfile_p4_2"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p4ImgFrame img").eq(2).attr("src",filepath);
				$("#p4LookDetail2").html('<img src="'+filepath+'" width="50" />');
			}
		});
		global_obj.file_upload($("#upfile_p4_3"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p4ImgFrame img").eq(3).attr("src",filepath);
				$("#p4LookDetail3").html('<img src="'+filepath+'" width="50" />');
			}
		});
		global_obj.file_upload($("#upfile_p4_4"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p4ImgFrame img").eq(4).attr("src",filepath);
				$("#p4LookDetail4").html('<img src="'+filepath+'" width="50" />');
			}
		});
	},
	//??????????????????
	"Evt1"	 : "",
	"Evt2"	 : "onmouseover='this.style.border=\"1px #3270AD solid\"' onmouseout = 'this.style.border = \"1px #F0F0EE solid\"' ",
	"Evt3"	 : "onmouseover='this.src=\""+domain.static+"/member/images/web/diy/select_1.png\"' onmouseout = 'this.src=\""+domain.static+"/member/images/web/diy/select.png\"'",
	"cloIco" : domain.static+"/member/images/web/diy/del.png",
	"firstrproperty" : "", 
	"p1"	: { //????????????
		"packageElement"	: function(packageName){
			$str = "";
			$str += "<div class='p1 sprite1' packageName='"+packageName+"' link0='' link1=''>";	
			$str += 	"<div class='dragPart'>";
			$str +=  		"<div class='p1ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????1</div>";
			$str +=				"<div class='wordObj'>????????????1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p1ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????2</div>";
			$str +=				"<div class='wordObj'>????????????2</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p1\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=  	"<div class='clean'></div>";
			$str += "</div>";
			return $str;
		},
		"insertWord"	: function(packageName){
			for(var i=0;i<2;i++)
			{
				var $value = $(".ps2_frmae_p1 .img_name").eq(i).find("input").val();
				if($(".selectObj").hasClass(packageName)){
					if($value){
						$(".selectObj .wordObj").eq(i).show().text($value);
					} else {
						$(".selectObj .wordObj").eq(i).hide();
						$(".selectObj .wordObj").eq(i).html("");
					}
				}
			}
			shop_obj.btnCtrl();
		}
	},
	
	"p0"	: { //????????????
		"packageElement"	: function(packageName){
			$str = "";
			$str += "<div class='p0 sprite1' packageName='"+packageName+"' link0='' link1='' link2=''>";	
			$str += 	"<div class='dragPart'>";
			$str +=  		"<div class='p0ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????1</div>";
			$str +=				"<div class='wordObj'>????????????1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p0ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????2</div>";
			$str +=				"<div class='wordObj'>????????????2</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p0ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????3</div>";
			$str +=				"<div class='wordObj'>????????????3</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p0\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=  	"<div class='clean'></div>";
			$str += "</div>";
			return $str;
		},
		"insertWord"	: function(packageName){
			for(var i=0;i<3;i++)
			{
				var $value = $(".ps2_frmae_p0 .img_name").eq(i).find("input").val();
				if($(".selectObj").hasClass(packageName)){
					if($value){
						$(".selectObj .wordObj").eq(i).show().text($value);
					} else {
						$(".selectObj .wordObj").eq(i).hide();
						$(".selectObj .wordObj").eq(i).html("");
					}
				}
			}
			shop_obj.btnCtrl();
		}
	},

	"p6"	: { //????????????
		"packageElement"	: function(packageName){
			$str = "";
			$str += "<div class='p6 sprite1' packageName='"+packageName+"' link0='' link1='' link2='' link3=''>";	
			$str += 	"<div class='dragPart'>";
			$str +=  		"<div class='p6ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????1</div>";
			$str +=				"<div class='wordObj'>????????????1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p6ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????2</div>";
			$str +=				"<div class='wordObj'>????????????2</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p6ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????3</div>";
			$str +=				"<div class='wordObj'>????????????3</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p6ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????4</div>";
			$str +=				"<div class='wordObj'>????????????4</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p6\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=  	"<div class='clean'></div>";
			$str += "</div>";
			return $str;
		},
		"insertWord"	: function(packageName){
			for(var i=0;i<4;i++)
			{
				var $value = $(".ps2_frmae_p6 .img_name").eq(i).find("input").val();
				if($(".selectObj").hasClass(packageName)){
					if($value){
						$(".selectObj .wordObj").eq(i).show().text($value);
					} else {
						$(".selectObj .wordObj").eq(i).hide();
						$(".selectObj .wordObj").eq(i).html("");
					}
				}
			}
			shop_obj.btnCtrl();
		}
	},

	"p7"	: { //????????????
		"packageElement"	: function(packageName){
			$str = "";
			$str += "<div class='p7 sprite1' packageName='"+packageName+"' link0='' link1='' link2='' link3='' link4=''>";	
			$str += 	"<div class='dragPart'>";
			$str +=  		"<div class='p7ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????1</div>";
			$str +=				"<div class='wordObj'>????????????1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p7ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????2</div>";
			$str +=				"<div class='wordObj'>????????????2</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p7ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????3</div>";
			$str +=				"<div class='wordObj'>????????????3</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p7ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????4</div>";
			$str +=				"<div class='wordObj'>????????????4</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p7ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????5</div>";
			$str +=				"<div class='wordObj'>????????????5</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p7\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=  	"<div class='clean'></div>";
			$str += "</div>";
			return $str;
		},
		"insertWord"	: function(packageName){
			for(var i=0;i<5;i++)
			{
				var $value = $(".ps2_frmae_p7 .img_name").eq(i).find("input").val();
				if($(".selectObj").hasClass(packageName)){
					if($value){
						$(".selectObj .wordObj").eq(i).show().text($value);
					} else {
						$(".selectObj .wordObj").eq(i).hide();
						$(".selectObj .wordObj").eq(i).html("");
					}
				}
			}
			shop_obj.btnCtrl();
		}
	},
	
	"p8"	: { //????????????
		"packageElement"	: function(packageName){
			$str = "";
			$str += "<div class='p8 sprite1' packageName='"+packageName+"' link0='' link1='' link2=''>";	
			$str += 	"<div class='dragPart'><div class='left8'>";
			$str +=  		"<div class='p8ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????1</div>";
			$str +=				"<div class='wordObj'>????????????1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div></div>";
			$str +=  		"<div class='right8'><div class='p8ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????2</div>";
			$str +=				"<div class='wordObj'>????????????2</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p8ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????3</div>";
			$str +=				"<div class='wordObj'>????????????3</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div></div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p8\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=  	"<div class='clean'></div>";
			$str += "</div>";
			return $str;
		},
		"insertWord"	: function(packageName){
			for(var i=0;i<3;i++)
			{
				var $value = $(".ps2_frmae_p8 .img_name").eq(i).find("input").val();
				if($(".selectObj").hasClass(packageName)){
					if($value){
						$(".selectObj .wordObj").eq(i).show().text($value);
					} else {
						$(".selectObj .wordObj").eq(i).hide();
						$(".selectObj .wordObj").eq(i).html("");
					}
				}
			}
			shop_obj.btnCtrl();
		}
	},
	
	"p9"	: { //????????????
		"packageElement"	: function(packageName){
			$str = "";
			$str += "<div class='p9 sprite1' packageName='"+packageName+"' link0='' link1='' link2='' link3='' link4=''>";	
			$str += 	"<div class='dragPart'><div class='left9'>";
			$str +=  		"<div class='p9ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????1</div>";
			$str +=				"<div class='wordObj'>????????????1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div></div>";
			$str +=  		"<div class='right9'><div class='p9ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????2</div>";
			$str +=				"<div class='wordObj'>????????????2</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p9ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????3</div>";
			$str +=				"<div class='wordObj'>????????????3</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p9ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????4</div>";
			$str +=				"<div class='wordObj'>????????????4</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p9ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????5</div>";
			$str +=				"<div class='wordObj'>????????????5</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div><div class='clean'></div></div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p9\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=  	"<div class='clean'></div>";
			$str += "</div>";
			return $str;
		},
		"insertWord"	: function(packageName){
			for(var i=0;i<5;i++)
			{
				var $value = $(".ps2_frmae_p9 .img_name").eq(i).find("input").val();
				if($(".selectObj").hasClass(packageName)){
					if($value){
						$(".selectObj .wordObj").eq(i).show().text($value);
					} else {
						$(".selectObj .wordObj").eq(i).hide();
						$(".selectObj .wordObj").eq(i).html("");
					}
				}
			}
			shop_obj.btnCtrl();
		}
	},
	
	"p10"	: { //????????????
		"packageElement"	: function(packageName){
			$str = "";
			$str += "<div class='p10 sprite1' packageName='"+packageName+"' link0='' link1='' link2=''>";	
			$str += 	"<div class='dragPart'><div class='left10'>";
			$str +=  		"<div class='p10ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????1</div>";
			$str +=				"<div class='wordObj'>????????????1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p10ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????2</div>";
			$str +=				"<div class='wordObj'>????????????2</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div></div>";
			$str +=  		"<div class='right10'><div class='p10ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????3</div>";
			$str +=				"<div class='wordObj'>????????????3</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div></div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p10\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=  	"<div class='clean'></div>";
			$str += "</div>";
			return $str;
		},
		"insertWord"	: function(packageName){
			for(var i=0;i<3;i++)
			{
				var $value = $(".ps2_frmae_p8 .img_name").eq(i).find("input").val();
				if($(".selectObj").hasClass(packageName)){
					if($value){
						$(".selectObj .wordObj").eq(i).show().text($value);
					} else {
						$(".selectObj .wordObj").eq(i).hide();
						$(".selectObj .wordObj").eq(i).html("");
					}
				}
			}
			shop_obj.btnCtrl();
		}
	},
	
	"p11"	: { //????????????
		"packageElement"	: function(packageName){
			$str = "";
			$str += "<div class='p11 sprite1' packageName='"+packageName+"' link0='' link1='' link2='' link3='' link4=''>";	
			$str += 	"<div class='dragPart'><div class='left11'>";
			$str +=  		"<div class='p11ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????1</div>";
			$str +=				"<div class='wordObj'>????????????1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p11ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????2</div>";
			$str +=				"<div class='wordObj'>????????????2</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p11ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????3</div>";
			$str +=				"<div class='wordObj'>????????????3</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p11ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????4</div>";
			$str +=				"<div class='wordObj'>????????????4</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div><div class='clean'></div></div>";
			$str +=  		"<div class='right11'><div class='p11ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????5</div>";
			$str +=				"<div class='wordObj'>????????????5</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div></div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p11\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=  	"<div class='clean'></div>";
			$str += "</div>";
			return $str;
		},
		"insertWord"	: function(packageName){
			for(var i=0;i<5;i++)
			{
				var $value = $(".ps2_frmae_p9 .img_name").eq(i).find("input").val();
				if($(".selectObj").hasClass(packageName)){
					if($value){
						$(".selectObj .wordObj").eq(i).show().text($value);
					} else {
						$(".selectObj .wordObj").eq(i).hide();
						$(".selectObj .wordObj").eq(i).html("");
					}
				}
			}
			shop_obj.btnCtrl();
		}
	},

	"p2"	: { //????????????
		"packageElement"	: function(packageName){
			$str  = "";
			$str += "<div class='p2 sprite1' packageName='"+packageName+"' link0=''>";
			$str += 	"<div class='dragPart'>??????????????????????????????</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p2\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=	"</div>";
			return $str;
		},
		"insertHtml"	: function(packageName){
			var $html = editor.html();
			if($(".selectObj").hasClass(packageName)){
				$(".selectObj .dragPart").html($html);
			}
			shop_obj.btnCtrl();
		}
	},

	"p3"	: { //????????????
		"packageElement"	: function(packageName){
			$str  = "";
			$str += "<div class='p3 sprite1' packageName='"+packageName+"' link0=''>";
			$str += 	"<div class='dragPart'>";
			$str +=  		"<div class='p3ImgFrame'>";
			$str +=				"<div class='imgObj'>????????????1</div>";
			$str +=				"<div class='wordObj'>????????????1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p3\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=		"<div class='clean'></div>";
			$str +=	"</div>";
			return $str;
		},
		"insertWord"	: function(packageName){ //????????????
			var $value = $(".ps2_frmae_p3 .img_name input").val();
			if($(".selectObj").hasClass(packageName)){
				if($value){
					$(".selectObj .wordObj").show().text($value);
				} else {
					$(".selectObj .wordObj").hide();
					$(".selectObj .wordObj").html("");
				}
			}
			shop_obj.btnCtrl();
		}
	},

	"p4"	: { //???????????????
		"packageElement"	: function(packageName){
			$str  = "";
			$str += "<div class='p4 sprite1' packageName='"+packageName+"' link0='' link1='' link2='' link3='' link4=''>";
			$str += 	"<div class='dragPart'>";
			$str +=  		"<div class='p4ImgFrame'>";
			$str +=				"<span>???????????????</span>";
			$str += 			"<img width='292' style='display:none' /><img width='292' style='display:none' /><img width='292' style='display:none' /><img width='292' style='display:none' /><img width='292' style='display:none' />";
			$str +=			"</div>"
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p4\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=	"</div>";
			return $str;
		},
		"insertWord"	: function(){
			shop_obj.btnCtrl();
		}
	},

	"p5"	: { //??????
		"packageElement"	: function(packageName){
			$str  = "";
			$str += "<div class='p5 sprite1' packageName='"+packageName+"' onclick='shop_obj.selectElement(this,\"p5\")'>";
			$str += 	"<div class='dragPart'></div>";
			$str +=		"<div class='delObj hand' onclick='shop_obj.delObjEvt(this,\"p5\");'><img "+shop_obj.Evt1+" src='"+shop_obj.cloIco+"' /></div>";
			$str +=	"</div>";
			return $str;
		},
		"insertHtml"	: function(packageName){
			var $html = $(".phoneSprite textarea").val();
			$html = $html.replace(/\n/g, "<br />");
			if($(".selectObj").hasClass(packageName)){
				$(".selectObj .dragPart").html($html);
			}
			shop_obj.btnCtrl();
		}
	},

	//------------ ??????0end ------------//
	
	//------------ ????????????start ------------//
	"delObjEvt" : function(e,packageName){	//???????????? 
		if(pName == packageName){
			pName = "";
		}
		if(confirm("????????????????????????????")){
			$(e).parents("."+packageName).remove();
		}
	},
	"selectElement" : function(e,packageName){
		var obj = $(e).parent("."+packageName);
		pName = packageName;
		$(obj).css("border","1px #f00 dashed").addClass("selectObj").siblings().css("border","1px #ccc dashed").removeClass("selectObj");
		shop_obj.showproElement(packageName);
	},
	"showproElement": function(packageName){ //????????????????????????
		switch(packageName)
		{
			case "p1"://????????????
				$(".ipad .selectObj").find(".wordObj").each(function(index, element) {
                    var $html = $(element).text();
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $color0 = $(".ipad .selectObj").attr("color0");
					var $color1 = $(".ipad .selectObj").attr("color1");
					var $bg0    = $(".ipad .selectObj").attr("background0");
					var $bg1    = $(".ipad .selectObj").attr("background1");
					$color0 = $color0?$color0:"#ffffff";
					$color1 = $color1?$color1:"#ffffff";
					$bg0    = $bg0?$bg0:"#4C4C4C";
					$bg1    = $bg1?$bg1:"#4C4C4C";
					
					$("#colorSelectorWordp1_0").val($color0).css("background",$color0);
					$("#colorSelectorWordp1_1").val($color1).css("background",$color1);
					$("#colorSelectorBgp1_0").val($bg0).css("background",$bg0);
					$("#colorSelectorBgp1_1").val($bg1).css("background",$bg1);

					$(".ps2_frmae_p1 .img_name input").eq(index).val($html);
					$(".ps2_frmae_p1 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p1 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p1")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
                });
			break;
			
			case "p0"://????????????
				$(".ipad .selectObj").find(".wordObj").each(function(index, element) {
                    var $html = $(element).text();
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $color0 = $(".ipad .selectObj").attr("color0");
					var $color1 = $(".ipad .selectObj").attr("color1");
					var $color2 = $(".ipad .selectObj").attr("color2");
					var $bg0    = $(".ipad .selectObj").attr("background0");
					var $bg1    = $(".ipad .selectObj").attr("background1");
					var $bg2    = $(".ipad .selectObj").attr("background2");
					$color0 = $color0?$color0:"#ffffff";
					$color1 = $color1?$color1:"#ffffff";
					$color2 = $color2?$color2:"#ffffff";
					$bg0    = $bg0?$bg0:"#4C4C4C";
					$bg1    = $bg1?$bg1:"#4C4C4C";
					$bg2    = $bg2?$bg2:"#4C4C4C";
					
					$("#colorSelectorWordp0_0").val($color0).css("background",$color0);
					$("#colorSelectorWordp0_1").val($color1).css("background",$color1);
					$("#colorSelectorWordp0_2").val($color2).css("background",$color2);
					$("#colorSelectorBgp0_0").val($bg0).css("background",$bg0);
					$("#colorSelectorBgp0_1").val($bg1).css("background",$bg1);
					$("#colorSelectorBgp0_2").val($bg2).css("background",$bg2);
					$(".ps2_frmae_p0 .img_name input").eq(index).val($html);
					$(".ps2_frmae_p0 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p0 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p0")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
					
                });
			break;
			
			case "p6"://????????????
				$(".ipad .selectObj").find(".wordObj").each(function(index, element) {
                    var $html = $(element).text();
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $color0 = $(".ipad .selectObj").attr("color0");
					var $color1 = $(".ipad .selectObj").attr("color1");
					var $color2 = $(".ipad .selectObj").attr("color2");
					var $color3 = $(".ipad .selectObj").attr("color3");
					var $bg0    = $(".ipad .selectObj").attr("background0");
					var $bg1    = $(".ipad .selectObj").attr("background1");
					var $bg2    = $(".ipad .selectObj").attr("background2");
					var $bg3    = $(".ipad .selectObj").attr("background3");
					$color0 = $color0?$color0:"#ffffff";
					$color1 = $color1?$color1:"#ffffff";
					$color2 = $color2?$color2:"#ffffff";
					$color3 = $color3?$color3:"#ffffff";
					$bg0    = $bg0?$bg0:"#4C4C4C";
					$bg1    = $bg1?$bg1:"#4C4C4C";
					$bg2    = $bg2?$bg2:"#4C4C4C";
					$bg3    = $bg3?$bg3:"#4C4C4C";
					
					$("#colorSelectorWordp6_0").val($color0).css("background",$color0);
					$("#colorSelectorWordp6_1").val($color1).css("background",$color1);
					$("#colorSelectorWordp6_2").val($color2).css("background",$color2);
					$("#colorSelectorWordp6_3").val($color3).css("background",$color3);
					$("#colorSelectorBgp6_0").val($bg0).css("background",$bg0);
					$("#colorSelectorBgp6_1").val($bg1).css("background",$bg1);
					$("#colorSelectorBgp6_2").val($bg2).css("background",$bg2);
					$("#colorSelectorBgp6_3").val($bg3).css("background",$bg3);
					$(".ps2_frmae_p6 .img_name input").eq(index).val($html);
					$(".ps2_frmae_p6 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p6 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p6")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
					
                });
			break;
			
			case "p7"://????????????
				$(".ipad .selectObj").find(".wordObj").each(function(index, element) {
                    var $html = $(element).text();
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $color0 = $(".ipad .selectObj").attr("color0");
					var $color1 = $(".ipad .selectObj").attr("color1");
					var $color2 = $(".ipad .selectObj").attr("color2");
					var $color3 = $(".ipad .selectObj").attr("color3");
					var $color4 = $(".ipad .selectObj").attr("color4");
					var $bg0    = $(".ipad .selectObj").attr("background0");
					var $bg1    = $(".ipad .selectObj").attr("background1");
					var $bg2    = $(".ipad .selectObj").attr("background2");
					var $bg3    = $(".ipad .selectObj").attr("background3");
					var $bg4    = $(".ipad .selectObj").attr("background4");
					$color0 = $color0?$color0:"#ffffff";
					$color1 = $color1?$color1:"#ffffff";
					$color2 = $color2?$color2:"#ffffff";
					$color3 = $color3?$color3:"#ffffff";
					$color4 = $color4?$color4:"#ffffff";
					$bg0    = $bg0?$bg0:"#4C4C4C";
					$bg1    = $bg1?$bg1:"#4C4C4C";
					$bg2    = $bg2?$bg2:"#4C4C4C";
					$bg3    = $bg3?$bg3:"#4C4C4C";
					$bg4    = $bg4?$bg4:"#4C4C4C";
					
					$("#colorSelectorWordp7_0").val($color0).css("background",$color0);
					$("#colorSelectorWordp7_1").val($color1).css("background",$color1);
					$("#colorSelectorWordp7_2").val($color2).css("background",$color2);
					$("#colorSelectorWordp7_3").val($color3).css("background",$color3);
					$("#colorSelectorWordp7_4").val($color4).css("background",$color4);
					$("#colorSelectorBgp7_0").val($bg0).css("background",$bg0);
					$("#colorSelectorBgp7_1").val($bg1).css("background",$bg1);
					$("#colorSelectorBgp7_2").val($bg2).css("background",$bg2);
					$("#colorSelectorBgp7_3").val($bg3).css("background",$bg3);
					$("#colorSelectorBgp7_4").val($bg4).css("background",$bg4);
					$(".ps2_frmae_p7 .img_name input").eq(index).val($html);
					$(".ps2_frmae_p7 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p7 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p7")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
					
                });
			break;
			
			case "p8"://????????????
				$(".ipad .selectObj").find(".wordObj").each(function(index, element) {
                    var $html = $(element).text();
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $color0 = $(".ipad .selectObj").attr("color0");
					var $color1 = $(".ipad .selectObj").attr("color1");
					var $color2 = $(".ipad .selectObj").attr("color2");
					var $bg0    = $(".ipad .selectObj").attr("background0");
					var $bg1    = $(".ipad .selectObj").attr("background1");
					var $bg2    = $(".ipad .selectObj").attr("background2");
					$color0 = $color0?$color0:"#ffffff";
					$color1 = $color1?$color1:"#ffffff";
					$color2 = $color2?$color2:"#ffffff";
					$bg0    = $bg0?$bg0:"#4C4C4C";
					$bg1    = $bg1?$bg1:"#4C4C4C";
					$bg2    = $bg2?$bg2:"#4C4C4C";
					
					$("#colorSelectorWordp8_0").val($color0).css("background",$color0);
					$("#colorSelectorWordp8_1").val($color1).css("background",$color1);
					$("#colorSelectorWordp8_2").val($color2).css("background",$color2);
					$("#colorSelectorBgp8_0").val($bg0).css("background",$bg0);
					$("#colorSelectorBgp8_1").val($bg1).css("background",$bg1);
					$("#colorSelectorBgp8_2").val($bg2).css("background",$bg2);
					$(".ps2_frmae_p8 .img_name input").eq(index).val($html);
					$(".ps2_frmae_p8 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p8 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p8")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
					
                });
			break;
			
			case "p9"://????????????
				$(".ipad .selectObj").find(".wordObj").each(function(index, element) {
                    var $html = $(element).text();
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $color0 = $(".ipad .selectObj").attr("color0");
					var $color1 = $(".ipad .selectObj").attr("color1");
					var $color2 = $(".ipad .selectObj").attr("color2");
					var $color3 = $(".ipad .selectObj").attr("color3");
					var $color4 = $(".ipad .selectObj").attr("color4");
					var $bg0    = $(".ipad .selectObj").attr("background0");
					var $bg1    = $(".ipad .selectObj").attr("background1");
					var $bg2    = $(".ipad .selectObj").attr("background2");
					var $bg3    = $(".ipad .selectObj").attr("background3");
					var $bg4    = $(".ipad .selectObj").attr("background4");
					$color0 = $color0?$color0:"#ffffff";
					$color1 = $color1?$color1:"#ffffff";
					$color2 = $color2?$color2:"#ffffff";
					$color3 = $color3?$color3:"#ffffff";
					$color4 = $color4?$color4:"#ffffff";
					$bg0    = $bg0?$bg0:"#4C4C4C";
					$bg1    = $bg1?$bg1:"#4C4C4C";
					$bg2    = $bg2?$bg2:"#4C4C4C";
					$bg3    = $bg3?$bg3:"#4C4C4C";
					$bg4    = $bg4?$bg4:"#4C4C4C";
					
					$("#colorSelectorWordp9_0").val($color0).css("background",$color0);
					$("#colorSelectorWordp9_1").val($color1).css("background",$color1);
					$("#colorSelectorWordp9_2").val($color2).css("background",$color2);
					$("#colorSelectorWordp9_3").val($color3).css("background",$color3);
					$("#colorSelectorWordp9_4").val($color4).css("background",$color4);
					$("#colorSelectorBgp9_0").val($bg0).css("background",$bg0);
					$("#colorSelectorBgp9_1").val($bg1).css("background",$bg1);
					$("#colorSelectorBgp9_2").val($bg2).css("background",$bg2);
					$("#colorSelectorBgp9_3").val($bg3).css("background",$bg3);
					$("#colorSelectorBgp9_4").val($bg4).css("background",$bg4);
					$(".ps2_frmae_p9 .img_name input").eq(index).val($html);
					$(".ps2_frmae_p9 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p9 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p9")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
					
                });
			break;
			
			case "p10"://????????????
				$(".ipad .selectObj").find(".wordObj").each(function(index, element) {
                    var $html = $(element).text();
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $color0 = $(".ipad .selectObj").attr("color0");
					var $color1 = $(".ipad .selectObj").attr("color1");
					var $color2 = $(".ipad .selectObj").attr("color2");
					var $bg0    = $(".ipad .selectObj").attr("background0");
					var $bg1    = $(".ipad .selectObj").attr("background1");
					var $bg2    = $(".ipad .selectObj").attr("background2");
					$color0 = $color0?$color0:"#ffffff";
					$color1 = $color1?$color1:"#ffffff";
					$color2 = $color2?$color2:"#ffffff";
					$bg0    = $bg0?$bg0:"#4C4C4C";
					$bg1    = $bg1?$bg1:"#4C4C4C";
					$bg2    = $bg2?$bg2:"#4C4C4C";
					
					$("#colorSelectorWordp10_0").val($color0).css("background",$color0);
					$("#colorSelectorWordp10_1").val($color1).css("background",$color1);
					$("#colorSelectorWordp10_2").val($color2).css("background",$color2);
					$("#colorSelectorBgp10_0").val($bg0).css("background",$bg0);
					$("#colorSelectorBgp10_1").val($bg1).css("background",$bg1);
					$("#colorSelectorBgp10_2").val($bg2).css("background",$bg2);
					$(".ps2_frmae_p10 .img_name input").eq(index).val($html);
					$(".ps2_frmae_p10 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p10 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p10")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
					
                });
			break;
			
			case "p11"://????????????
				$(".ipad .selectObj").find(".wordObj").each(function(index, element) {
                    var $html = $(element).text();
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $color0 = $(".ipad .selectObj").attr("color0");
					var $color1 = $(".ipad .selectObj").attr("color1");
					var $color2 = $(".ipad .selectObj").attr("color2");
					var $color3 = $(".ipad .selectObj").attr("color3");
					var $color4 = $(".ipad .selectObj").attr("color4");
					var $bg0    = $(".ipad .selectObj").attr("background0");
					var $bg1    = $(".ipad .selectObj").attr("background1");
					var $bg2    = $(".ipad .selectObj").attr("background2");
					var $bg3    = $(".ipad .selectObj").attr("background3");
					var $bg4    = $(".ipad .selectObj").attr("background4");
					$color0 = $color0?$color0:"#ffffff";
					$color1 = $color1?$color1:"#ffffff";
					$color2 = $color2?$color2:"#ffffff";
					$color3 = $color3?$color3:"#ffffff";
					$color4 = $color4?$color4:"#ffffff";
					$bg0    = $bg0?$bg0:"#4C4C4C";
					$bg1    = $bg1?$bg1:"#4C4C4C";
					$bg2    = $bg2?$bg2:"#4C4C4C";
					$bg3    = $bg3?$bg3:"#4C4C4C";
					$bg4    = $bg4?$bg4:"#4C4C4C";
					
					$("#colorSelectorWordp11_0").val($color0).css("background",$color0);
					$("#colorSelectorWordp11_1").val($color1).css("background",$color1);
					$("#colorSelectorWordp11_2").val($color2).css("background",$color2);
					$("#colorSelectorWordp11_3").val($color3).css("background",$color3);
					$("#colorSelectorWordp11_4").val($color4).css("background",$color4);
					$("#colorSelectorBgp11_0").val($bg0).css("background",$bg0);
					$("#colorSelectorBgp11_1").val($bg1).css("background",$bg1);
					$("#colorSelectorBgp11_2").val($bg2).css("background",$bg2);
					$("#colorSelectorBgp11_3").val($bg3).css("background",$bg3);
					$("#colorSelectorBgp11_4").val($bg4).css("background",$bg4);
					$(".ps2_frmae_p11 .img_name input").eq(index).val($html);
					$(".ps2_frmae_p11 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p11 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p11")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
					
                });
			break;
			
			case "p2"://??????
				var $html = $(".ipad .selectObj").find(".dragPart").html();
				var $link = $(".ipad .selectObj").attr("link0");
				editor.html($html);
				$(".ps2_frmae_p2 .selectLink select").val($link);
				$(".ps2_frmae_p2 .selectLink select").change(function(){
					var $value = $(this).val(); //??????????????????
					if($(".selectObj").hasClass("p2")){
						$(".selectObj").attr("link0",$value);
					}
				});
			break;
			
			case "p3"://??????
				var $html = $(".ipad .selectObj").find(".wordObj").text();
				var $link = $(".ipad .selectObj").attr("link0");
				var $color0 = $(".ipad .selectObj").attr("color0");
				var $bg0    = $(".ipad .selectObj").attr("background0");
				$color0 = $color0?$color0:"#ffffff";
				$bg0    = $bg0?$bg0:"#4C4C4C";
				
				$("#colorSelectorWordp3_0").val($color0).css("background",$color0);
				$("#colorSelectorBgp3_0").val($bg0).css("background",$bg0);
				
				$(".ps2_frmae_p3 .img_name input").val($html);
				$(".ps2_frmae_p3 .selectLink select").val($link);
				$(".ps2_frmae_p3 .selectLink select").change(function(){
					var $value = $(this).val(); //??????????????????
					if($(".selectObj").hasClass("p3")){
						$(".selectObj").attr("link0",$value);
					}
				});
			break;
			
			case "p4"://?????????
				$(".ps2_frmae_p4 .lookDetail").html("");
				$(".ipad .selectObj .p4ImgFrame").find("img").each(function(index, element) {
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $src  = $(this).attr("src");
					if($src){
						$(".ps2_frmae_p4 .lookDetail").eq(index).html("<img src='"+$src+"' width='50' />");
					}
					$(".ps2_frmae_p4 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p4 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p4")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
                });
			break;
			
			case "p5"://????????????
				var $html = $(".ipad .selectObj").find(".dragPart").html();
				var $color= $(".ipad .selectObj").attr("color0");
				var $bg   = $(".ipad .selectObj").attr("background0");
				var $fz   = $(".ipad .selectObj").attr("fontsize0");
				$color = ($color=="" || $color=="undefined")?"#ffffff":$color;
				$bg    = ($bg=="" || $bg=="undefined")?"#4C4C4C":$bg;
				$fz    = ($fz=="" || $fz=="undefined")?"14":$fz;
				
				
				$("#colorSelectorWordp5").css("background",$color).val($color);
				$("#colorSelectorBgp5").css("background",$bg).val($bg);
				$(".fontSize_p5").val($fz);
				
				$html = $html.replace(/<br \/>/g, "<br>");
				$html = $html.replace(/<BR \/>/g, "<br>");
				$html = $html.replace(/<BR>/g, "<br>");
				$html = $html.replace(/<br>/g, "\n");
				$(".phoneSprite textarea").val($html);
			break;
			
		}
		$(".ps2_frmae_"+packageName).show().siblings().hide();
	},
	"colorPicker": function(){
		//p1 ????????????
		$('#colorSelectorWordp1_0,#colorSelectorWordp1_1').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp1_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp1_0,#colorSelectorBgp1_1').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp1_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		
		//p0 ????????????
		$('#colorSelectorWordp0_0,#colorSelectorWordp0_1,#colorSelectorWordp0_2').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp0_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp0_0,#colorSelectorBgp0_1,#colorSelectorBgp0_2').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp0_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		
		//p6 ????????????
		$('#colorSelectorWordp6_0,#colorSelectorWordp6_1,#colorSelectorWordp6_2,#colorSelectorWordp6_3').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp6_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp6_0,#colorSelectorBgp6_1,#colorSelectorBgp6_2,#colorSelectorBgp6_3').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp6_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		
		//p7 ????????????
		$('#colorSelectorWordp7_0,#colorSelectorWordp7_1,#colorSelectorWordp7_2,#colorSelectorWordp7_3,#colorSelectorWordp7_4').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp7_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp7_0,#colorSelectorBgp7_1,#colorSelectorBgp7_2,#colorSelectorBgp7_3,#colorSelectorBgp7_4').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp7_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		
		//p8 ????????????
		$('#colorSelectorWordp8_0,#colorSelectorWordp8_1,#colorSelectorWordp8_2').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp8_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp8_0,#colorSelectorBgp8_1,#colorSelectorBgp8_2').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp8_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		
		//p9 ????????????
		$('#colorSelectorWordp9_0,#colorSelectorWordp9_1,#colorSelectorWordp9_2,#colorSelectorWordp9_3,#colorSelectorWordp9_4').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp9_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp9_0,#colorSelectorBgp9_1,#colorSelectorBgp9_2,#colorSelectorBgp9_3,#colorSelectorBgp9_4').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp9_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		
		//p10 ????????????
		$('#colorSelectorWordp10_0,#colorSelectorWordp10_1,#colorSelectorWordp10_2').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp10_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp10_0,#colorSelectorBgp10_1,#colorSelectorBgp10_2').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp10_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		
		//p11 ????????????
		$('#colorSelectorWordp11_0,#colorSelectorWordp11_1,#colorSelectorWordp11_2,#colorSelectorWordp11_3,#colorSelectorWordp11_4').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp11_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp11_0,#colorSelectorBgp11_1,#colorSelectorBgp11_2,#colorSelectorBgp11_3,#colorSelectorBgp11_4').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp11_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		
		//p3 ????????????
		$('#colorSelectorWordp3_0').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp3_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp3_0').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp3_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		
		//p5 ????????????
		$('#colorSelectorWordp5').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .dragPart").css("color","#"+hex);
				$(".selectObj").attr("color0","#"+hex);
			}
		});
		$('#colorSelectorBgp5').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj").css("background","#"+hex);
				$(".selectObj").attr("background0","#"+hex);
			}
		});
		$(".fontSize_p5").change(function(){ //????????????
			var $value = $(this).val();
			$(".selectObj .dragPart").css("font-size",$value+"px");
			$(".selectObj").attr("fontsize0",$value);
		})
		
	},
	"btnCtrl"	: function(){
		var str 		 = "";
		//json??????
		var $html        = $(".ipad .sprite1").html();
		$(".ipad .sprite1").each(function(index, element) {
			var $packageName = $(this).attr("packagename");
			switch($packageName){
				case "p1": //????????????
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $color0		 = $(this).attr("color0");
					var $color1		 = $(this).attr("color1");
					var $bg0		 = $(this).attr("background0");
					var $bg1		 = $(this).attr("background1");
					var $img0		 = $(this).find(".p1ImgFrame").eq(0).find(".imgObj img").attr("src");
					var $img1		 = $(this).find(".p1ImgFrame").eq(1).find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p1ImgFrame").eq(0).find(".wordObj").text();
					var $txt1		 = $(this).find(".p1ImgFrame").eq(1).find(".wordObj").text();
						str += "{";
						str += 		'"type" : "p1",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+'",';
						str +=		'"txt"  : "'+$txt0+"|"+$txt1+'",';
						str +=		'"txtColor": "'+$color0+"|"+$color1+'",';
						str +=		'"bgColor" : "'+$bg0+"|"+$bg1+'"';
						str += "},";
				break;
				
				case "p0": //????????????
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $link2       = $(this).attr("link2");
					var $color0		 = $(this).attr("color0");
					var $color1		 = $(this).attr("color1");
					var $color2		 = $(this).attr("color2");
					var $bg0		 = $(this).attr("background0");
					var $bg1		 = $(this).attr("background1");
					var $bg2		 = $(this).attr("background2");
					var $img0		 = $(this).find(".p0ImgFrame").eq(0).find(".imgObj img").attr("src");
					var $img1		 = $(this).find(".p0ImgFrame").eq(1).find(".imgObj img").attr("src");
					var $img2		 = $(this).find(".p0ImgFrame").eq(2).find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p0ImgFrame").eq(0).find(".wordObj").text();
					var $txt1		 = $(this).find(".p0ImgFrame").eq(1).find(".wordObj").text();
					var $txt2		 = $(this).find(".p0ImgFrame").eq(2).find(".wordObj").text();
						str += "{";
						str += 		'"type" : "p0",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+"|"+$link2+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+"|"+$img2+'",';
						str +=		'"txt"  : "'+$txt0+"|"+$txt1+"|"+$txt2+'",';
						str +=		'"txtColor": "'+$color0+"|"+$color1+"|"+$color2+'",';
						str +=		'"bgColor" : "'+$bg0+"|"+$bg1+"|"+$bg2+'"';
						str += "},";
					
				break;
				
				case "p6": //????????????
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $link2       = $(this).attr("link2");
					var $link3       = $(this).attr("link3");
					var $color0		 = $(this).attr("color0");
					var $color1		 = $(this).attr("color1");
					var $color2		 = $(this).attr("color2");
					var $color3		 = $(this).attr("color3");
					var $bg0		 = $(this).attr("background0");
					var $bg1		 = $(this).attr("background1");
					var $bg2		 = $(this).attr("background2");
					var $bg3		 = $(this).attr("background3");
					var $img0		 = $(this).find(".p6ImgFrame").eq(0).find(".imgObj img").attr("src");
					var $img1		 = $(this).find(".p6ImgFrame").eq(1).find(".imgObj img").attr("src");
					var $img2		 = $(this).find(".p6ImgFrame").eq(2).find(".imgObj img").attr("src");
					var $img3		 = $(this).find(".p6ImgFrame").eq(3).find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p6ImgFrame").eq(0).find(".wordObj").text();
					var $txt1		 = $(this).find(".p6ImgFrame").eq(1).find(".wordObj").text();
					var $txt2		 = $(this).find(".p6ImgFrame").eq(2).find(".wordObj").text();
					var $txt3		 = $(this).find(".p6ImgFrame").eq(3).find(".wordObj").text();
						str += "{";
						str += 		'"type" : "p6",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+"|"+$link2+"|"+$link3+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+"|"+$img2+"|"+$img3+'",';
						str +=		'"txt"  : "'+$txt0+"|"+$txt1+"|"+$txt2+"|"+$txt3+'",';
						str +=		'"txtColor": "'+$color0+"|"+$color1+"|"+$color2+"|"+$color3+'",';
						str +=		'"bgColor" : "'+$bg0+"|"+$bg1+"|"+$bg2+"|"+$bg3+'"';
						str += "},";
					
				break;
				
				case "p7": //????????????
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $link2       = $(this).attr("link2");
					var $link3       = $(this).attr("link3");
					var $link4       = $(this).attr("link4");
					var $color0		 = $(this).attr("color0");
					var $color1		 = $(this).attr("color1");
					var $color2		 = $(this).attr("color2");
					var $color3		 = $(this).attr("color3");
					var $color4		 = $(this).attr("color4");
					var $bg0		 = $(this).attr("background0");
					var $bg1		 = $(this).attr("background1");
					var $bg2		 = $(this).attr("background2");
					var $bg3		 = $(this).attr("background3");
					var $bg4		 = $(this).attr("background4");
					var $img0		 = $(this).find(".p7ImgFrame").eq(0).find(".imgObj img").attr("src");
					var $img1		 = $(this).find(".p7ImgFrame").eq(1).find(".imgObj img").attr("src");
					var $img2		 = $(this).find(".p7ImgFrame").eq(2).find(".imgObj img").attr("src");
					var $img3		 = $(this).find(".p7ImgFrame").eq(3).find(".imgObj img").attr("src");
					var $img4		 = $(this).find(".p7ImgFrame").eq(4).find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p7ImgFrame").eq(0).find(".wordObj").text();
					var $txt1		 = $(this).find(".p7ImgFrame").eq(1).find(".wordObj").text();
					var $txt2		 = $(this).find(".p7ImgFrame").eq(2).find(".wordObj").text();
					var $txt3		 = $(this).find(".p7ImgFrame").eq(3).find(".wordObj").text();
					var $txt4		 = $(this).find(".p7ImgFrame").eq(4).find(".wordObj").text();
						str += "{";
						str += 		'"type" : "p7",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+"|"+$link2+"|"+$link3+"|"+$link4+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+"|"+$img2+"|"+$img3+"|"+$img4+'",';
						str +=		'"txt"  : "'+$txt0+"|"+$txt1+"|"+$txt2+"|"+$txt3+"|"+$txt4+'",';
						str +=		'"txtColor": "'+$color0+"|"+$color1+"|"+$color2+"|"+$color3+"|"+$color4+'",';
						str +=		'"bgColor" : "'+$bg0+"|"+$bg1+"|"+$bg2+"|"+$bg3+"|"+$bg4+'"';
						str += "},";
					
				break;
				
				case "p8": //????????????
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $link2       = $(this).attr("link2");
					var $color0		 = $(this).attr("color0");
					var $color1		 = $(this).attr("color1");
					var $color2		 = $(this).attr("color2");
					var $bg0		 = $(this).attr("background0");
					var $bg1		 = $(this).attr("background1");
					var $bg2		 = $(this).attr("background2");
					var $img0		 = $(this).find(".p8ImgFrame").eq(0).find(".imgObj img").attr("src");
					var $img1		 = $(this).find(".p8ImgFrame").eq(1).find(".imgObj img").attr("src");
					var $img2		 = $(this).find(".p8ImgFrame").eq(2).find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p8ImgFrame").eq(0).find(".wordObj").text();
					var $txt1		 = $(this).find(".p8ImgFrame").eq(1).find(".wordObj").text();
					var $txt2		 = $(this).find(".p8ImgFrame").eq(2).find(".wordObj").text();
						str += "{";
						str += 		'"type" : "p8",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+"|"+$link2+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+"|"+$img2+'",';
						str +=		'"txt"  : "'+$txt0+"|"+$txt1+"|"+$txt2+'",';
						str +=		'"txtColor": "'+$color0+"|"+$color1+"|"+$color2+'",';
						str +=		'"bgColor" : "'+$bg0+"|"+$bg1+"|"+$bg2+'"';
						str += "},";
					
				break;
				
				case "p9": //????????????
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $link2       = $(this).attr("link2");
					var $link3       = $(this).attr("link3");
					var $link4       = $(this).attr("link4");
					var $color0		 = $(this).attr("color0");
					var $color1		 = $(this).attr("color1");
					var $color2		 = $(this).attr("color2");
					var $color3		 = $(this).attr("color3");
					var $color4		 = $(this).attr("color4");
					var $bg0		 = $(this).attr("background0");
					var $bg1		 = $(this).attr("background1");
					var $bg2		 = $(this).attr("background2");
					var $bg3		 = $(this).attr("background3");
					var $bg4		 = $(this).attr("background4");
					var $img0		 = $(this).find(".p9ImgFrame").eq(0).find(".imgObj img").attr("src");
					var $img1		 = $(this).find(".p9ImgFrame").eq(1).find(".imgObj img").attr("src");
					var $img2		 = $(this).find(".p9ImgFrame").eq(2).find(".imgObj img").attr("src");
					var $img3		 = $(this).find(".p9ImgFrame").eq(3).find(".imgObj img").attr("src");
					var $img4		 = $(this).find(".p9ImgFrame").eq(4).find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p9ImgFrame").eq(0).find(".wordObj").text();
					var $txt1		 = $(this).find(".p9ImgFrame").eq(1).find(".wordObj").text();
					var $txt2		 = $(this).find(".p9ImgFrame").eq(2).find(".wordObj").text();
					var $txt3		 = $(this).find(".p9ImgFrame").eq(3).find(".wordObj").text();
					var $txt4		 = $(this).find(".p9ImgFrame").eq(4).find(".wordObj").text();
						str += "{";
						str += 		'"type" : "p9",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+"|"+$link2+"|"+$link3+"|"+$link4+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+"|"+$img2+"|"+$img3+"|"+$img4+'",';
						str +=		'"txt"  : "'+$txt0+"|"+$txt1+"|"+$txt2+"|"+$txt3+"|"+$txt4+'",';
						str +=		'"txtColor": "'+$color0+"|"+$color1+"|"+$color2+"|"+$color3+"|"+$color4+'",';
						str +=		'"bgColor" : "'+$bg0+"|"+$bg1+"|"+$bg2+"|"+$bg3+"|"+$bg4+'"';
						str += "},";
					
				break;
				
				case "p10": //????????????
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $link2       = $(this).attr("link2");
					var $color0		 = $(this).attr("color0");
					var $color1		 = $(this).attr("color1");
					var $color2		 = $(this).attr("color2");
					var $bg0		 = $(this).attr("background0");
					var $bg1		 = $(this).attr("background1");
					var $bg2		 = $(this).attr("background2");
					var $img0		 = $(this).find(".p10ImgFrame").eq(0).find(".imgObj img").attr("src");
					var $img1		 = $(this).find(".p10ImgFrame").eq(1).find(".imgObj img").attr("src");
					var $img2		 = $(this).find(".p10ImgFrame").eq(2).find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p10ImgFrame").eq(0).find(".wordObj").text();
					var $txt1		 = $(this).find(".p10ImgFrame").eq(1).find(".wordObj").text();
					var $txt2		 = $(this).find(".p10ImgFrame").eq(2).find(".wordObj").text();
						str += "{";
						str += 		'"type" : "p10",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+"|"+$link2+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+"|"+$img2+'",';
						str +=		'"txt"  : "'+$txt0+"|"+$txt1+"|"+$txt2+'",';
						str +=		'"txtColor": "'+$color0+"|"+$color1+"|"+$color2+'",';
						str +=		'"bgColor" : "'+$bg0+"|"+$bg1+"|"+$bg2+'"';
						str += "},";
					
				break;
				
				case "p11": //????????????
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $link2       = $(this).attr("link2");
					var $link3       = $(this).attr("link3");
					var $link4       = $(this).attr("link4");
					var $color0		 = $(this).attr("color0");
					var $color1		 = $(this).attr("color1");
					var $color2		 = $(this).attr("color2");
					var $color3		 = $(this).attr("color3");
					var $color4		 = $(this).attr("color4");
					var $bg0		 = $(this).attr("background0");
					var $bg1		 = $(this).attr("background1");
					var $bg2		 = $(this).attr("background2");
					var $bg3		 = $(this).attr("background3");
					var $bg4		 = $(this).attr("background4");
					var $img0		 = $(this).find(".p11ImgFrame").eq(0).find(".imgObj img").attr("src");
					var $img1		 = $(this).find(".p11ImgFrame").eq(1).find(".imgObj img").attr("src");
					var $img2		 = $(this).find(".p11ImgFrame").eq(2).find(".imgObj img").attr("src");
					var $img3		 = $(this).find(".p11ImgFrame").eq(3).find(".imgObj img").attr("src");
					var $img4		 = $(this).find(".p11ImgFrame").eq(4).find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p11ImgFrame").eq(0).find(".wordObj").text();
					var $txt1		 = $(this).find(".p11ImgFrame").eq(1).find(".wordObj").text();
					var $txt2		 = $(this).find(".p11ImgFrame").eq(2).find(".wordObj").text();
					var $txt3		 = $(this).find(".p11ImgFrame").eq(3).find(".wordObj").text();
					var $txt4		 = $(this).find(".p11ImgFrame").eq(4).find(".wordObj").text();
						str += "{";
						str += 		'"type" : "p11",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+"|"+$link2+"|"+$link3+"|"+$link4+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+"|"+$img2+"|"+$img3+"|"+$img4+'",';
						str +=		'"txt"  : "'+$txt0+"|"+$txt1+"|"+$txt2+"|"+$txt3+"|"+$txt4+'",';
						str +=		'"txtColor": "'+$color0+"|"+$color1+"|"+$color2+"|"+$color3+"|"+$color4+'",';
						str +=		'"bgColor" : "'+$bg0+"|"+$bg1+"|"+$bg2+"|"+$bg3+"|"+$bg4+'"';
						str += "},";
					
				break;
				
				case "p2": //??????
					var $link0       = $(this).attr("link0");
					var $txt0		 = $(this).find(".dragPart").html();
					$txt0 = $txt0.replace(/\"/g, '&quot;');
					$txt0 = $txt0.replace(/\n/g, '');
					$txt0 = $txt0.replace(/\t/g, '');
					
						str += "{";
						str += 		'"type" : "p2",';
						str +=  	'"url"  : "'+$link0+'",';
						str +=		'"pic"  : "",';
						str +=		'"txt"  : "'+$txt0+'"';
						str += "},";
				break;
				
				case "p3": //??????
					var $link0       = $(this).attr("link0");
					var $img0		 = $(this).find(".p3ImgFrame").find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p3ImgFrame").find(".wordObj").text();
					var $color0		 = $(this).attr("color0");
					var $bg0		 = $(this).attr("background0");
						str += "{";
						str += 		'"type" : "p3",';
						str +=  	'"url"  : "'+$link0+'",';
						str +=		'"pic"  : "'+$img0+'",';
						str +=		'"txt"  : "'+$txt0+'",';
						str +=		'"txtColor": "'+$color0+'",';
						str +=		'"bgColor" : "'+$bg0+'"';
						str += "},";
				break;
				
				case "p4": //?????????
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $link2       = $(this).attr("link2");
					var $link3       = $(this).attr("link3");
					var $link4       = $(this).attr("link4");
					var $img0		 = $(this).find(".p4ImgFrame img").eq(0).attr("src");
					var $img1		 = $(this).find(".p4ImgFrame img").eq(1).attr("src");
					var $img2		 = $(this).find(".p4ImgFrame img").eq(2).attr("src");
					var $img3		 = $(this).find(".p4ImgFrame img").eq(3).attr("src");
					var $img4		 = $(this).find(".p4ImgFrame img").eq(4).attr("src");
						str += "{";
						str += 		'"type" : "p4",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+"|"+$link2+"|"+$link3+"|"+$link4+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+"|"+$img2+"|"+$img3+"|"+$img4+'",';
						str +=		'"txt"  : ""';
						str += "},";
				break;
				
				case "p5": //??????
					var $txt0		 = $(this).find(".dragPart").html();
					var $color0		 = $(this).attr("color0");
					var $bg0		 = $(this).attr("background0");
					var $fz0		 = $(this).attr("fontsize0");
						str += "{";
						str += 		'"type" : "p5",';
						str +=  	'"url"  : "",';
						str +=		'"pic"  : "",';
						str +=		'"txtColor": "'+$color0+'",';
						str +=		'"bgColor" : "'+$bg0+'",';
						str +=		'"fontSize" : "'+$fz0+'",';
						str +=		'"txt"  : "'+$txt0+'"';
						str += "},";
				break;
				
			}
		});
		//json??????
		str = "["+str.substr(0,str.length-1)+"]"; //json end
		$.ajax({
			type    : "POST",
			url     : "?",
			data    : "gruopPackage="+encodeURIComponent(str)+"&do_action=shop.home_diy&date="+new Date(),
			dataType:"json",
			success: function(data){	
				if(data.status == 1){
					alert("?????????????????????");
				}
			}
		});
	},
	//------------ ????????????end ------------//
	
}