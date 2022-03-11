<?php

include_once("../../include/admin.inc.php");

?>
<html>
<head>
<title>Qmail Toaster Admin</title>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" href="/scripts/styles.css" type="text/css" />
<script language="JavaScript" src="/scripts/javascripts.js"></script>
<script language="JavaScript">
<!--

function CheckFormChangePassword(form) {
	
	var numErrors = '' ;
	var errors = '' ;
		
	if (!isValidLength(form.oldpasswd.value, 2, 30)) {
		errors += 'Please type your OLD PASSWORD.\n';
		numErrors++;
	}
	
	if (!isValidLength(form.newpasswd.value, 2, 30)) {
		errors += 'Please type your NEW PASSWORD.\n';
		numErrors++;
	}
	
	if (!isValidLength(form.newpasswd2.value, 2, 30)) {
		errors += 'Please retype your NEW PASSWORD.\n';
		numErrors++;
	}

	if (form.newpasswd2.value != form.newpasswd.value) {
		errors += 'Please be sure NEW PASSWORD retype is right.\n';
		numErrors++;
	}
	
	if (numErrors) {
		errors = 'Attention! ' + ((numErrors > 1) ? 'Theese' : 'This') + ' error' + ((numErrors > 1) ? 's' : '') + ' ' + ((numErrors > 1) ? 'were' : 'was') + ' detected :\n\n' + errors + '\n';
       		alert(errors);
         	return false;
  	}
  
  	return true;
	
}

-->
</script>
</head>
<body text="#000000" vlink="#004400" alink="#ff0000" link="#007700" bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" background="/images-toaster/background.gif">
<center>
  <form action="<?php print ''; ?>" method="POST" onSubmit="return CheckFormChangePassword(this)">
  <table width="750" border="0" cellpadding="0" cellspacing="0">
    <tbody> 
    <tr> 
      <td width="203"><a href="http://www.qmail.org/"><img height="163" alt="logo" src="/images-toaster/kl-qmail-w.gif" width="200" border="0"></a> </td>
      <td align="center" valign="middle"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody><tr align="center" valign="middle"> 
            <td> 
              <h1><font color="#006600"><b>Qmail Toaster Admin</b></font></h1>
            </td>
          </tr>
        </tbody></table>
      </td>
    </tr>

    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">Change Admin Password:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2"><br>
      <?php print_change_passwd($_POST['oldpasswd'], $_POST['newpasswd'], $_POST['newpasswd2']); ?>
      <br></td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">Quick Go:</font></b></td>
    </tr>
    <tr> 
      <td colspan="2"><br>
      <?php print_quick_go(); ?>      
      <br></td>
    </tr>
    </tbody> 
  </table>
  <br><i><a href="http://qmailtoaster.com" target="_blank">Qmail Toaster &copy 2004-2014</a></i><br>
</form>
</center>
</body></html>
