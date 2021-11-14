
<?php

include 'head.php';
 


?>



<table align="center" cellpadding="0" cellspacing="0" class="table_list_98">

  <tr>

    <td valign="top">

		
<table cellpadding="3" cellspacing="0" class="table_98">

		 <form action="agentlist.php?" method="post" name="form1">

		  <tr>

			<td >代理商编号：<input type="text" name="agentid" size="20" value="<?=$agentid?>" /><br />
手机号：<input type="text" name="phone" size="10"  value="<?=$phone?>"><br />
微信号：<input type="text" name="weixin" size="10" value="<?=$weixin?>"><br />
姓名：<input type="text" name="name" size="10" value="<?=$name?>" />

			  <input type="hidden" name="pz" id="pz" value="<?=$pz?>" />

			 <br />
 <input name="submit" class="alertify-button alertify-button-cancel" type="submit" id="submit" value="查找"> </td>

		  </tr>

		 </form>

		</table>
			

	



	      

	</td>

  </tr>

</table>



</div> 
 </section>



          

    </div>
</body>

</html>