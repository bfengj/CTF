<?php
session_start();
//if(!session_is_registered('Adminname'))
if(!$_SESSION["Adminname"])
{
 echo "<script>location.href='index.php';</script>";
 exit;
}
?>
