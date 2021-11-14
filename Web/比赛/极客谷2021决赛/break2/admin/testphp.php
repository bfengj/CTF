
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
        <li class="cur"><a href="config.php">基本设置</a></li>
        <li><a href="other_config.php">活动设置</a></li>
        <li><a href="skin.php">风格设置</a></li>
        <li><a href="home.php">首页设置</a></li>
        <li><a href="menu_config.php">菜单配置</a></li>
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
            <td width="50%" valign="top"><h1><span class="fc_red">*</span> <strong>微商城名称</strong></h1>
              <input type="text" class="input" name="ShopName" value="test的微商城" maxlength="30" notnull /></td>
            <td width="50%" valign="top" style="display:none;"><h1><strong>需要物流</strong><span class="tips">（关闭后下订单无需填写收货地址）</span></h1>
              <div class="input">
				<label>需要<input type="radio" checked  value="1" name="NeedShipping"></label>&nbsp;&nbsp;
				<label>不需要<input type="radio"  value="0" name="NeedShipping"></label>
               </div></td>
          </tr>
          <tr>
            <td width="50%" valign="top"><h1><strong>订单确认</strong><span class="tips">（关闭后下订单可直接付款，无需经过卖家确认）</span></h1>
              <div class="input">
				<label>关闭<input type="radio" checked  value="1" name="CheckOrder"></label>&nbsp;&nbsp;
				<label>开启<input type="radio"  value="0" name="CheckOrder"></label>
				<span class="tips">(只针对在线付款有效)</span>
              </div></td>
              <td width="50%" valign="top"><h1><strong>评论审核</strong><span class="tips">（关闭后客户评论可不经过审核直接显示在前台页面）</span></h1>
              <div class="input">
				<label>关闭<input type="radio" checked  value="1" name="CommitCheck"></label>&nbsp;&nbsp;
				<label>开启<input type="radio"  value="0" name="CommitCheck"></label>
				<span class="tips"></span>
              </div>
              </td>
          </tr>
		  <tr>
            <td width="50%" valign="top">
             <h1><strong>一键拨号</strong>
                <input type="checkbox" name="CallEnable" value="1" />
                <span class="tips">启用</span></h1>
              <input type="text" class="input" name="CallPhoneNumber" value="" maxlength="20" />
            </td>
            <td width="50%" valign="top">
			<h1><span class="fc_red">*</span> <strong>订单自动确认收货时间(单位是天)</strong></h1>
              <input type="text" class="input" name="Confirm_Time" value="0" size="10" notnull />
            </td>
          </tr>
          
          <tr>
            <td width="50%" valign="top">
              <h1><strong>订单手机短信通知</strong>
                <input type="checkbox" name="SendSms" value="1" />
                <span class="tips">启用（填接收短信的手机号）</span></h1>
              <input type="text" class="input" name="MobilePhone" style="width:120px" value="" maxlength="11" /><span class="tips"> 短信剩余 <font style="color:red">0</font> 条&nbsp;&nbsp;<a href="/member/sms/sms_add.php" style="color:#F60; text-decoration:underline">点击购买</a></span>
            </td>
            <td width="50%" valign="top">
            	<h1><strong>分销商分享获取积分</strong>
                <input type="checkbox" name="DistributeShare" value="1" checked />
                <span class="tips">开启</span></h1>
              <input type="text" class="input" name="DistributeShareScore" style="width:50px" value="20" />
            </td>              
          </tr>
          <tr>
            <td width="50%" valign="top">
              <h1><strong>非分销商分享获取积分</strong>
                <input type="checkbox" name="MemberShare" value="1" checked />
                <span class="tips">开启</span></h1>
              <input type="text" class="input" name="MemberShareScore" style="width:50px" value="10" />
            </td>
            <td width="50%" valign="top">
               <h1><strong>商城关注提醒</strong>
                <input type="checkbox" name="Substribe" value="1" />
                <span class="tips">启用(若用户未关注公众号，则提示用户关注)</span></h1>
              <input type="text" class="input" name="SubstribeUrl" value="" placeholder="请填写链接地址" />
            </td>              
          </tr>
		  <tr>
			<td width="50%" valign="top">
               <h1><strong>会员首次关注获取积分</strong>
                <input type="checkbox" name="SubstribeScore" value="1" checked />
                <span class="tips">启用</span></h1>
              <input type="text" class="input" name="Member_SubstribeScore" style="width:50px" value="100" />
            </td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
            <td width="50%" valign="top">
			  <h1><strong>logo</strong></h1>
                        <div id="card_style">
                            <div class="file">
                                <span class="fc_red">（请上传png透明格式）</span><br />
                                <span class="tips">&nbsp;&nbsp;尺寸建议：100*100px</span><br /><br />
                                <input name="LogoUpload" id="LogoUpload" type="button" style="width:80px;" value="上传图片" /><br /><br />
                                <div class="img" id="LogoDetail">
                                <img src="/static/api/images/user/face.jpg" />								
                                </div>
								<input type="hidden" id="Logo" name="Logo" value="/static/api/images/user/face.jpg" />
                            </div>
                            <div class="clear"></div>
                        </div>
            </td>
			<td width="50%" valign="top">
				<h1><strong>自定义分享图片</strong></h1>
                        <div id="card_style">
                            <div class="file">
                                <span class="tips">&nbsp;&nbsp;尺寸建议：100*100px</span><br /><br />
                                <input name="ShareLogoUpload" id="ShareLogoUpload" type="button" style="width:80px;" value="上传图片" /><br /><br />
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
			  <h1><strong>商城公告</strong></h1>
              <textarea name="ShopAnnounce"></textarea>
            </td>
			<td width="50%" valign="top">            
               <h1><strong>自定义分享语</strong></h1>
              <textarea name="ShareIntro"></textarea>
            </td>
          </tr>
          
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><h1><strong>触发信息设置</strong></h1>
              <div class="reply_msg">
                <div class="m_left"> <span class="fc_red">*</span> 触发关键词<span class="tips_key">（有多个关键词请用 <font style="color:red">"|"</font> 隔开）</span><br />
                  <input type="text" class="input" name="Keywords" value="微商城" maxlength="100" notnull />
                  <br />
                  <br />
                  <br />
                  <span class="fc_red">*</span> 匹配模式<br />
                  <div class="input">
                    <input type="radio" name="PatternMethod" value="0" checked />
                    精确匹配<span class="tips">（输入的文字和此关键词一样才触发）</span></div>
                  <div class="input">
                    <input type="radio" name="PatternMethod" value="1" />
                    模糊匹配<span class="tips">（输入的文字包含此关键词就触发）</span></div>
                  <br />
                  <br />
                  <span class="fc_red">*</span> 图文消息标题<br />
                  <input type="text" class="input" name="Title" value="微商城" maxlength="100" notnull />
                </div>
                <div class="m_right">
					<span class="fc_red">*</span> 图文消息封面<span class="tips">（大图尺寸建议：640*360px）</span><br />
                       <div class="file"><input name="ReplyImgUpload" id="ReplyImgUpload" type="button" style="width:80px;" value="上传图片" /></div><br />
                       <div class="img" id="ReplyImgDetail">
                       <img src="/static/api/images/cover_img/shop.jpg" />                    </div>
                </div>
                <div class="clear"></div>
              </div>
              <input type="hidden" id="ReplyImgPath" name="ImgPath" value="/static/api/images/cover_img/shop.jpg" /></td>
          </tr>
        </table>
        <div class="submit">
          <input type="submit" name="submit_button" value="提交保存" />
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>