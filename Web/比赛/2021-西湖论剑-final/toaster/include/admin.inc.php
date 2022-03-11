<?php

DEFINE("BASEDIR", "/usr/share/toaster/");

# 01/03/14 shubes - don't know why this is here - throws warning
#                 - I don't see what purpose it could serve, so commented it
# $file = $_GET['file'];

function read_file($file) {

	if ( is_readable ( $file ) ) {
		$fd = fopen ( $file, "r" ) ;
		$contents = fread ( $fd, filesize( $file ) ) ;
		fclose ( $fd ) ;
		$contents = preg_replace("/\n/", "", $contents ) ;
		$contents = preg_replace("/\r/", "", $contents ) ;
		return $contents;
	} else {
		return false;
	}

}

function write_file($contents="", $file) {

	if ( is_writable ( $file ) && strlen( $contents ) >= 1 ) {
		$fd = fopen ( $file, "w" ) ;
		fwrite ( $fd, $contents ) ;
		fclose ( $fd ) ;
		return true ;
	} else {
		return false;
	}

}

function verify_old_password($oldpasswd) {

	if ( $oldpasswd == read_file( BASEDIR . "include/admin.pass" ) ) {
		return true;
	} else {
		return false;
	}

}

function write_new_password($newpasswd) {

	if ( write_file( $newpasswd , BASEDIR . "include/admin.pass" ) ) {
		return true;		
	} else {
		return false;
	}
		
}

function change_admin_password($oldpasswd, $newpasswd, $newpasswd2) {

	if (verify_old_password($oldpasswd) && isset($newpasswd) && ( $newpasswd == $newpasswd2 ) ) {
		write_new_password($newpasswd) ;
		exec( BASEDIR . "include/htpasswd -bc " . BASEDIR . "include/admin.htpasswd admin " . $newpasswd);
		return "Password updated successfully!" ;
	} else {
		return "Password update failure!" ;
	}

}

function print_table($contents = "") {

	$header = '<table border="0" cellspacing="0" cellpadding="3" width="100%">' ;
	$footer = '</table>' ;
	
	if ( $contents ) {
		printf("%s\n%s\n%s\n", $header, $contents, $footer ) ;
	} else {
		printf("%s\n<tr><td>\n<b>No contents to display!</b>\n</td></tr>\n%s", $header, $footer ) ;
	}
		
}

function print_date( $time = "" ) {

	 print strftime ("%m %d %Y %H:%I:%S", ( ($time != "") ? $time : time() ) );
	 return;

}

function print_change_passwd($oldpasswd="", $newpasswd="", $newpasswd2="") {

	unset ( $html ) ;
	$html = '';
	
	$msg   = change_admin_password($oldpasswd, $newpasswd, $newpasswd2) ;
	
	$html .= '<tr>';
	$html .= '<td align="right" width="47%">Type Old Password</td>' ;
	$html .= '<td width="6%">&nbsp;</td>';
	$html .= '<td align="left" width=47%"><input type="password" name="oldpasswd" class="inputs"></td>' ;
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td align="right" width="47%">Type New Password</td>' ;
	$html .= '<td width="6%">&nbsp;</td>';
	$html .= '<td align="left" width=47%"><input type="password" name="newpasswd" class="inputs"></td>' ;
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td align="right" width="47%">Retype New Password</td>' ;
	$html .= '<td width="6%">&nbsp;</td>';
	$html .= '<td align="left" width=47%"><input type="password" name="newpasswd2" class="inputs"></td>' ;
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td align="right" width="47%">&nbsp;</td>' ;
	$html .= '<td width="6%">&nbsp;</td>';
	$html .= '<td align="left" width=47%"><input type="submit" value="Update Password" class="inputs"></td>' ;
	$html .= '</tr>';
	
	if ($msg && $oldpasswd && $newpasswd && $newpasswd2) {

		$html .= '<tr>';
		$html .= '<td align="center" colspan="3"><br>' . $msg . '</td>' ;
		$html .= '</tr>';
		
	}
	
	print_table( $html ) ;
	
}

function print_quick_go() { // Modular
	
	unset ( $html ) ;
	$html = '';
	
	$dir  = chdir( BASEDIR . "/include" );
	$hd   = opendir (".");
	
	while ( $file = readdir ( $hd ) ) {

		if ( substr ( $file, -7 ) == ".module" ) {
		
			$fd 	= fopen ( $file, "r" ) ;
			$html  .= fread ( $fd, filesize ( $file ) ) ;
			fclose ( $fd ) ;

		}

	}
	
	print_table( $html ) ;
	
}

# 01/03/14 shubes - this function does not appear to be implemented anywhere
function print_updates() {

	$start="<!-- TABELLA PACCHETTI RPM -->";
	$stop="<!-- FINE TABELLA PACCHETTI RPM -->" ;
	$masterUrl="http://qmailtoaster.com";
	if ( $fd = @fopen( $masterUrl , "r" ) ) {
		$contents = fread($fd, 200000);
		eregi("$start(.*)$stop", $contents, $html);
		$html = ereg_replace("src=\"/imgs/", "src=\"$masterUrl/imgs/", $html[1] ) ;
		$html = ereg_replace("href=\"download/", "href=\"$masterUrl/download/", $html );
	} else {
		$html = "<tr><td>No updates avaiable!</td></tr>";
	}
	print_table( $html ) ;
}

function find_all_user_emails() {

	$mysql = read_qmail_sql();

	$connessione = mysql_pconnect( $mysql['host'], $mysql['user'], $mysql['pass'] ) ;
	mysql_select_db( $mysql['database'] ) ;

	$domains = find_all_domains ();

	for ( $i=0; $i<sizeof ( $domains ); $i++ ) {

	$result = mysql_query ( "select pw_name from " . $domains[$i] ) ;

	while ( $user = mysql_fetch_array ( $result ) ) {

		$users[] = $user[0] . "@" . str_replace ("_", ".", $domains[$i] ) ;

	}

	}

	return $users;

}

function find_all_domains () {
	
	$mysql = read_qmail_sql();

	$connessione = mysql_pconnect( $mysql['host'], $mysql['user'], $mysql['pass'] ) ;
	$result = mysql_list_tables ( $mysql['database'] ) ;

	while ( $row = mysql_fetch_row ( $result ) ) {

		if ( $row[0] != "dir_control" && $row[0] != "lastauth" && $row[0] != "relay" && $row[0] != "vlog" ) {

			$domains[] = $row[0] ;

		}

	}
	
	return $domains ;

}

function print_notify_users( $from = "", $emailText = "", $subject = "") {

	unset ( $html ) ;
	$html = '';
	
	if ( $subject && $from && $emailText ) {

		$html .= '<tr>';
		$html .= '<td colspan="3" align="center">Emails were sent to...<br><br>';
		
		$users = find_all_user_emails() ;
		
		for ( $i=0; $i<sizeof ( $users ); $i++ ) {

		mail ( $users[$i], stripslashes( $subject ), stripslashes( $emailText ), "From: $from <$from>\r\nSender: $from <$from>\r\nReturn-Path: $from <$from>\r\n");
		$html .= $users[$i] . '<br>' ;

		}
		$html .= '</td>' ;
		$html .= '</tr>';

	} else {
	
		$html .= '<tr>';
		$html .= '<td colspan="3" align="center" valign="top">' ;
		$html .= '<table border="0" cellspacing="0" cellpadding="0" width="100%"
			  <tr><td align="center">
			  <textarea name="emailText" style="width:500; height:300; "></textarea>
			  </td></tr>
			  <tr><td>&nbsp;</td></tr>
			  <tr><td align="center"><input type="text" name="from" onFocus="pulisci_input_type(this)" value="your@email" class="inputs"></td></tr>
			  <tr><td>&nbsp;</td></tr>
			  <tr><td align="center"><input type="text" name="subject" onFocus="pulisci_input_type(this)" value="subject" class="inputs"></td></tr>
			  <tr><td>&nbsp;</td></tr>
			  <tr><td align="center"><input type="submit" value="Send" class="inputs"></td></tr>
			  </table>';
		$html .= '</td>' ;
		$html .= '</tr>';

	}
	
	print_table( $html ) ;
	

}

function exec_mysql ( $sql = "", $type = "mysql_query" ) {

	
}

function read_qmail_sql() {

	$fd = popen ( BASEDIR . "/include/sql.sh", "r" ) ;
	$contents = fread ( $fd, 1024 ) ;
	pclose ( $fd ) ;

	$line = explode ( "\n", str_replace ( "\r", "", $contents ) ) ;

	for ( $i = 0; $i < sizeof ( $line ); $i++ ) {
		
		// We use substr instead of eregi... 
		// ... so user can be called user and password maybe called password
		if ( substr ( $line[ $i ], 0, 6 ) == "server" ) {
			$host = trim ( substr( $line[ $i ], 6 ) ) ;
		} else if ( substr ( $line[ $i ], 0, 4 ) == "port" ) {
			$port = trim ( substr( $line[ $i ], 4 ) ) ;
		} else if ( substr ( $line[ $i ], 0, 8 ) == "database" ) {
			$database = trim ( substr( $line[ $i ], 8 ) ) ;
		} else if ( substr ( $line[ $i ], 0, 4 ) == "user" ) {
			$user = trim ( substr( $line[ $i ], 4 ) ) ;
		} else if ( substr ( $line[ $i ], 0, 4 ) == "pass" ) {
			$pass = trim ( substr( $line[ $i ], 4 ) ) ;
		}

	}

	if ( $host && $port && $database && $user && $pass ) {

		return array ("host"=>$host, "port"=>$port, "database"=>$database, "user"=>$user, "pass"=>$pass ) ;

	} else {

		return false ;

	}
	
}

?>
