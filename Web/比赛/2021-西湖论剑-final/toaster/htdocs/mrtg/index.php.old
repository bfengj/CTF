<?php

$file = $_GET['file'];

function parse_mrtg( $file ) {

	$limitatori = array(
					"daily" => array ( "<!-- Begin `Daily' Graph \(10 Minute -->" , "<!-- End `Daily' Graph \(10 Minute -->" ),
					"weekly"=> array ( "<!-- Begin `Weekly' Graph \(30 Minute -->" , "<!-- End `Weekly' Graph \(30 Minute -->" ),
					"monthly" => array ( "<!-- Begin `Monthly' Graph \(2 Hour -->" , "<!-- End `Monthly' Graph \(2 Hour -->" ) ,
					"yearly"  => array ("<!-- Begin `Yearly' Graph \(1 Day -->" , "<!-- End `Yearly' Graph \(1 Day -->") ) ;
	
	$_file = sprintf("/usr/share/toaster/htdocs/mrtg/%s" , $file ) ;
	
	if ( $fd = @fopen( $_file , "r") ) {
		$contents = fread($fd, 200000);
		fclose ( $fd ) ;
	} else {
		return false;
	}
					
	while (list ($indice, $vettore) = each ( $limitatori ) ) {
					
		eregi($vettore[0] . "(.*)" . $vettore[1] , $contents, $buffer);
		$buffer = ereg_replace("SRC=\"", "src=\"/stats-toaster/?file=", $buffer[1] ) ;
		$buffer = ereg_replace("<HR>", "", $buffer );
		$html = sprintf("%s\n%s<br>", $html, $buffer ) ;
	
	}

	flush() ;
	
	return $html;
	
}

function show_graph ( $file ) {

	readfile("/usr/share/toaster/htdocs/mrtg/" . ereg_replace("/", "", $file) ) ;
	
	exit;
	
}

if ( isset ( $file ) ) {

	show_graph ( $file ) ;	
	
}

?>
<html>
<head>
<title>Qmail Toaster Admin</title>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" href="/scripts/styles.css" type="text/css" />
<script language="JavaScript" src="/scripts/javascripts.js"></script>
</head>
<body text="#000000" vlink="#004400" alink="#ff0000" link="#007700" bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" background="/images-toaster/background.gif">
<center>
  <form action="/admin-toaster/index.php" method="POST">
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
    <tr align="right"> 
       <td colspan="2"><b><a href="/stats-toaster/">STATISTICS</a> | <a href="/admin-toaster/">TOASTER ADMIN</a></b></td>
   </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Bytes:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("bytes.html" );
    ?>
      </td>
    </tr>
	<tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Concurrency:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("concurrency.html" );
    ?>
	<br>
      </td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Messages:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("messages.html" );
    ?>
	<br>
      </td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Message Status:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("messstatus.html" );
    ?>
	<br>
      </td>
    </tr>
     <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Queue Size:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("queue-size.html" );
    ?>
	<br>
      </td>
    </tr>
    <tr> 
<tr>
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Clamd:</font></b> </td>
    </tr>
    <tr>
      <td colspan="2" align="center"><br>
    <?php
        print parse_mrtg ("clamd.html" );
    ?>
        <br>
      </td>
    </tr>
    <tr>
<tr>
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Spamd:</font></b> </td>
    </tr>
    <tr>
      <td colspan="2" align="center"><br>
    <?php
        print parse_mrtg ("spamd.html" );
    ?>
        <br>
      </td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Smtp Allow/Deny:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("smtpad.html" );
    ?>
	<br>
      </td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Smtp:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("smtp.html" );
    ?>
	<br>
      </td>
    </tr>
   <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Imap4 Allow/Deny:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("imap4ad.html" );
    ?>
	<br>
      </td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Imap4:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("imap4.html" );
    ?>
	<br>
      </td>
    </tr>

    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Imap4-SSL Allow/Deny:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("imap4-sslad.html" );
    ?>
	<br>
      </td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Imap4-SSL:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("imap4-ssl.html" );
    ?>
	<br>
      </td>
    </tr>

<tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Pop3 Allow/Deny:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("pop3ad.html" );
    ?>
	<br>
      </td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Pop3:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("pop3.html" );
    ?>
	<br>
      </td>
    </tr>


<tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Pop3-SSL Allow/Deny:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("pop3-sslad.html" );
    ?>
	<br>
      </td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Pop3-SSL:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("pop3-ssl.html" );
    ?>
	<br>
      </td>
    </tr>
<!--     <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;CPU 0:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("cpu0.html" );
    ?>
	<br>
      </td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;System Load:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("load.html" );
    ?>
	<br>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Memory Usage:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("mem.html" );
    ?>
	<br>
      </td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Memory Swap:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("swap.html" );
    ?>
	<br>
      </td>
    </tr>
      </td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#007700"><b><font color="#ffffff">&nbsp;Traffic on eth0:</font></b> </td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><br>
    <?php
	print parse_mrtg ("eth0.html" );
    ?>
	<br>
      </td>
    </tr> -->
    </tbody> 
  </table>
  <br><i><a href="http://www.qmailtoaster.com" target="_blank">Qmail Toaster &copy 2006</a></i><br>
</form>
</center>
</body></html>
