<?php
session_start();
//if(!session_is_registered('Adminnamess'))
if(!$_SESSION["Adminnamess"])
{
 echo "<script>location.href='index.php';</script>";
 exit;
}
?>
