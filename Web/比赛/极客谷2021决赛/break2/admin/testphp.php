
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/shop.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="../editor/kindeditor-all.js"></script>
<script charset="utf-8" src="../editor/lang/zh-CN.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=web_article',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	
	K('#LogoUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#Logo').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#Logo').val(url);
					K('#LogoDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#ShareLogoUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ShareLogo').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ShareLogo').val(url);
					K('#ShareLogoDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#ReplyImgUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ReplyImgPath').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ReplyImgPath').val(url);
					K('#ReplyImgDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})
</script>
<style type="text/css">
#config_form img{width:100px; height:100px;}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
   
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="config.php">????????????</a></li>
        <li><a href="other_config.php">????????????</a></li>
        <li><a href="skin.php">????????????</a></li>
        <li><a href="home.php">????????????</a></li>
        <li><a href="menu_config.php">????????????</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">$(document).ready(function(){
		
		global_obj.config_form_init();
		shop_obj.confirm_form_init();
		
	});</script>
    <div class="r_con_config r_con_wrap">
      <form id="config_form" action="config.php" method="post">
        <table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="50%" valign="top"><h1><span class="fc_red">*</span> <strong>???????????????</strong></h1>
              <input type="text" class="input" name="ShopName" value="test????????????" maxlength="30" notnull /></td>
            <td width="50%" valign="top" style="display:none;"><h1><strong>????????????</strong><span class="tips">????????????????????????????????????????????????</span></h1>
              <div class="input">
				<label>??????<input type="radio" checked  value="1" name="NeedShipping"></label>&nbsp;&nbsp;
				<label>?????????<input type="radio"  value="0" name="NeedShipping"></label>
               </div></td>
          </tr>
          <tr>
            <td width="50%" valign="top"><h1><strong>????????????</strong><span class="tips">??????????????????????????????????????????????????????????????????</span></h1>
              <div class="input">
				<label>??????<input type="radio" checked  value="1" name="CheckOrder"></label>&nbsp;&nbsp;
				<label>??????<input type="radio"  value="0" name="CheckOrder"></label>
				<span class="tips">(???????????????????????????)</span>
              </div></td>
              <td width="50%" valign="top"><h1><strong>????????????</strong><span class="tips">????????????????????????????????????????????????????????????????????????</span></h1>
              <div class="input">
				<label>??????<input type="radio" checked  value="1" name="CommitCheck"></label>&nbsp;&nbsp;
				<label>??????<input type="radio"  value="0" name="CommitCheck"></label>
				<span class="tips"></span>
              </div>
              </td>
          </tr>
		  <tr>
            <td width="50%" valign="top">
             <h1><strong>????????????</strong>
                <input type="checkbox" name="CallEnable" value="1" />
                <span class="tips">??????</span></h1>
              <input type="text" class="input" name="CallPhoneNumber" value="" maxlength="20" />
            </td>
            <td width="50%" valign="top">
			<h1><span class="fc_red">*</span> <strong>??????????????????????????????(????????????)</strong></h1>
              <input type="text" class="input" name="Confirm_Time" value="0" size="10" notnull />
            </td>
          </tr>
          
          <tr>
            <td width="50%" valign="top">
              <h1><strong>????????????????????????</strong>
                <input type="checkbox" name="SendSms" value="1" />
                <span class="tips">???????????????????????????????????????</span></h1>
              <input type="text" class="input" name="MobilePhone" style="width:120px" value="" maxlength="11" /><span class="tips"> ???????????? <font style="color:red">0</font> ???&nbsp;&nbsp;<a href="/member/sms/sms_add.php" style="color:#F60; text-decoration:underline">????????????</a></span>
            </td>
            <td width="50%" valign="top">
            	<h1><strong>???????????????????????????</strong>
                <input type="checkbox" name="DistributeShare" value="1" checked />
                <span class="tips">??????</span></h1>
              <input type="text" class="input" name="DistributeShareScore" style="width:50px" value="20" />
            </td>              
          </tr>
          <tr>
            <td width="50%" valign="top">
              <h1><strong>??????????????????????????????</strong>
                <input type="checkbox" name="MemberShare" value="1" checked />
                <span class="tips">??????</span></h1>
              <input type="text" class="input" name="MemberShareScore" style="width:50px" value="10" />
            </td>
            <td width="50%" valign="top">
               <h1><strong>??????????????????</strong>
                <input type="checkbox" name="Substribe" value="1" />
                <span class="tips">??????(???????????????????????????????????????????????????)</span></h1>
              <input type="text" class="input" name="SubstribeUrl" value="" placeholder="?????????????????????" />
            </td>              
          </tr>
		  <tr>
			<td width="50%" valign="top">
               <h1><strong>??????????????????????????????</strong>
                <input type="checkbox" name="SubstribeScore" value="1" checked />
                <span class="tips">??????</span></h1>
              <input type="text" class="input" name="Member_SubstribeScore" style="width:50px" value="100" />
            </td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
            <td width="50%" valign="top">
			  <h1><strong>logo</strong></h1>
                        <div id="card_style">
                            <div class="file">
                                <span class="fc_red">????????????png???????????????</span><br />
                                <span class="tips">&nbsp;&nbsp;???????????????100*100px</span><br /><br />
                                <input name="LogoUpload" id="LogoUpload" type="button" style="width:80px;" value="????????????" /><br /><br />
                                <div class="img" id="LogoDetail">
                                <img src="/static/api/images/user/face.jpg" />								
                                </div>
								<input type="hidden" id="Logo" name="Logo" value="/static/api/images/user/face.jpg" />
                            </div>
                            <div class="clear"></div>
                        </div>
            </td>
			<td width="50%" valign="top">
				<h1><strong>?????????????????????</strong></h1>
                        <div id="card_style">
                            <div class="file">
                                <span class="tips">&nbsp;&nbsp;???????????????100*100px</span><br /><br />
                                <input name="ShareLogoUpload" id="ShareLogoUpload" type="button" style="width:80px;" value="????????????" /><br /><br />
                                <div class="img" id="ShareLogoDetail">
                                								
                                </div>
								<input type="hidden" id="ShareLogo" name="ShareLogo" value="" />
                            </div>
                            <div class="clear"></div>
                        </div>               
            </td>
          </tr>
		  
		  <tr>
            <td width="50%" valign="top">
			  <h1><strong>????????????</strong></h1>
              <textarea name="ShopAnnounce"></textarea>
            </td>
			<td width="50%" valign="top">            
               <h1><strong>??????????????????</strong></h1>
              <textarea name="ShareIntro"></textarea>
            </td>
          </tr>
          
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><h1><strong>??????????????????</strong></h1>
              <div class="reply_msg">
                <div class="m_left"> <span class="fc_red">*</span> ???????????????<span class="tips_key">??????????????????????????? <font style="color:red">"|"</font> ?????????</span><br />
                  <input type="text" class="input" name="Keywords" value="?????????" maxlength="100" notnull />
                  <br />
                  <br />
                  <br />
                  <span class="fc_red">*</span> ????????????<br />
                  <div class="input">
                    <input type="radio" name="PatternMethod" value="0" checked />
                    ????????????<span class="tips">???????????????????????????????????????????????????</span></div>
                  <div class="input">
                    <input type="radio" name="PatternMethod" value="1" />
                    ????????????<span class="tips">????????????????????????????????????????????????</span></div>
                  <br />
                  <br />
                  <span class="fc_red">*</span> ??????????????????<br />
                  <input type="text" class="input" name="Title" value="?????????" maxlength="100" notnull />
                </div>
                <div class="m_right">
					<span class="fc_red">*</span> ??????????????????<span class="tips">????????????????????????640*360px???</span><br />
                       <div class="file"><input name="ReplyImgUpload" id="ReplyImgUpload" type="button" style="width:80px;" value="????????????" /></div><br />
                       <div class="img" id="ReplyImgDetail">
                       <img src="/static/api/images/cover_img/shop.jpg" />                    </div>
                </div>
                <div class="clear"></div>
              </div>
              <input type="hidden" id="ReplyImgPath" name="ImgPath" value="/static/api/images/cover_img/shop.jpg" /></td>
          </tr>
        </table>
        <div class="submit">
          <input type="submit" name="submit_button" value="????????????" />
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>