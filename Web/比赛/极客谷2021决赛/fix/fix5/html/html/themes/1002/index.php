<!doctype html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?=$cf['site_name']?></title>

<meta name="keywords" content="<?=$cf['page_keywords']?>" />

<meta name="description" content="<?=$cf['page_desc']?>" />
	
		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="themes/<?=$cf['agent_themes']?>/style/css/animate.min.css">
		<link rel="stylesheet" href="themes/<?=$cf['agent_themes']?>/style/css/vegas.min.css"> 
		<!-- Theme CSS -->
        <link rel="stylesheet" href="themes/<?=$cf['agent_themes']?>/style/css/reset.css">
		<link rel="stylesheet" href="themes/<?=$cf['agent_themes']?>/style/css/style.css">
		<link rel="stylesheet" href="themes/<?=$cf['agent_themes']?>/style/css/mobile.css">
		<!--[if lt IE 9]>
			<script src="style/js/html5shiv.min.js"></script>
			<script src="style/js/respond.min.js"></script>
		<![endif]-->
        

<style type="text/css">
	.neibogg {
	padding-top: 20px;
	padding-right: 15px;
	padding-bottom: 10px;
	padding-left: 15px;
	color: #FFF;
	line-height:18px;
}
.neibogg a {
	color:#ffffff
}
    .boxxaa {
	width: 500px;
}
.tijiaobox {
		width: auto;
		font-style: normal;
		background: rgba(255,255,255,0.07);
		color: #dfdfdf;
		cursor: pointer;
		border-left: 1px solid rgba(255,255,255,0.1);
		border-right: 1px solid rgba(255,255,255,0.1);
		padding: 13px 25px;
	}
@media screen and (max-width: 800px) { 
.boxxaa {
	width: 320px;
}
.neibogg {
	padding-top: 20px;
	padding-right: 1px;
	padding-bottom: 10px;
	padding-left: 1px;
	color: #FFF;
	line-height:18px;
}
.row img{ width:270px;}
header {
	padding-top: 60px;
	padding-bottom: 0px;
}
#form { width:300px; margin:0 auto}
}
    </style>
	</head>
	<body>
    <script>
function CheckSearchForm(){
        if(document.getElementById("searchform").keyword.value=="")
        {
                alert("请输入微信号或手机号");
                return false;
        }
     
}
</script>
 <form name="searchform" id="searchform" method="get" action="search.php?act=query" onSubmit="return CheckSearchForm();">
   		<div class="page-loader"></div>
		<header>
			<div class="container">
				<div class="row">
<div class="logo wow bounceInLeft" data-wow-delay="0.4s"><img src="<?=$cf['agent_logo']?>"/></div>
				</div>
			</div>
		</header>
		<section id="content">
			<div class="container">
				<div class="row">
<div class="content-intro">


          <input type="hidden" name="act" id="act" value="query">
<div id="form">
<div class="form-container wow bounceInLeft" data-wow-delay="0.5s">
<div class="page-section">                                            	
<div class="subscriptionForm" >
<input type="hidden" name="classid" value="3">
<input type="hidden" name="show" value="title,weixin,tel">
<input type="text" name="keyword" id="keyword" class="emailfield" placeholder="请输入微信或手机号"/>
  <?php if($cf['yzm_status']==1){?>验证码：

   <input name='yzm' id='yzm' class="inputYzm" type="text" value="" maxlength="4">

   <img src="data/code.php" alt="验证码" title="点击刷新" class="code" onClick="window.location.reload()"/>&nbsp; <?php } ?>
   
   
<input type="submit" class="submit" id="submit" value="查 询" />
&nbsp;
<input onClick="window.open('AddInfo.php')"  type="button" class="tijiaobox" id="submit" value="申请" />


<div class="neibogg"><div class="contact">  

 

    <div id="tgs_result_str"><?php echo $result_str;?></div>

 

</div></div>
</div> 
</div>
</div>
</div>





</div>
				</div>
			</div>
		</section>
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<p class="wow bounceIn" data-wow-delay="0.7s"><?=$cf['copyrighta']?></p>
					</div>
				</div>
			</div>
		</footer>

   
   	<script src="themes/<?=$cf['agent_themes']?>/style/js/jquery-2.1.4.min.js"></script>
		<script src="themes/<?=$cf['agent_themes']?>/style/js/wow.min.js"></script>
		<script src="themes/<?=$cf['agent_themes']?>/style/js/vegas.min.js"></script>
		<script src="themes/<?=$cf['agent_themes']?>/style/js/theme.js"></script>
        </form>

	</body>
</html>