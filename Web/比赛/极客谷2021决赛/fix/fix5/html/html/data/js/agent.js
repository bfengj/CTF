/*
  代理商查询系统 js
*/
//创建ajax函数
function createXMLHTTP()
{
    var xmlhttp=null;
    if(window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest(); //firefox下执行此语句
    }
    else if(window.ActiveXObject){
        try{
          xmlhttp = new ActiveXObject("Msxm12.XMLHTTP");
        }catch(e){
            try{ // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(e){}
        }
    }
	return xmlhttp;
}
//全局XMLHTTP对象实力变量
var http  = createXMLHTTP();
//发送查询的请求
function GetQuery()
{
		var keyword     = document.getElementById("keyword").value;
		var show     = document.getElementById("show").value;
		var yzm_status  = document.getElementById("yzm_status").value;		
		var search      = document.getElementById("search").value;		
		if(yzm_status == 1){
		 var yzm        = document.getElementById("yzm").value;
		}		
		if(keyword == ""){
		  alert("微信号不能为空");
		  document.getElementById("keyword").focus();
		  return false;
		}
		if(yzm_status == 1 && yzm == ""){
		  alert("验证码不能为空");
		  document.getElementById("yzm").focus();
		  return false;
		}
		if(yzm_status == 1 && yzm.length != 4){
		  alert("验证码格式错误");
		  document.getElementById("yzm").focus();
		  return false;
		}
		////重新赋值
		document.getElementById("search").value = 'no';		
		if(search == ""){		
			var url="agent.php?act=query";			
			url+=("&keyword="+keyword+"&yzm="+yzm+"&show="+show+"&search="+search);
			http.open("GET",url,true);
			http.onreadystatechange=ProcessHttpResponse;
			http.send(null);
			return;
		}
}
//处理查询的请求
function ProcessHttpResponse()
{
	if(http.readyState==4)
	{
		if(http.status==200)
		{
			var jieguo = http.responseText;
			var arr_str=new Array();
			arr_str= jieguo.split("|");

			document.getElementById("result_agent").innerHTML  = arr_str[1];
		}
		else
		{
			alert("error:"+http.status);
		}
	}
}
