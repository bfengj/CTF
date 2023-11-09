<?php
session_start();
error_reporting(0);
date_default_timezone_set('PRC');
header("Content-type: text/html; charset=utf-8"); 

$url='http://zcglxt.ccn';

$dbhost='localhost';
$dbname='zcglxt_ccn';
$dbuser='XXXX';
$dbpwd='XXXX';
$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
mysqli_query($con, 'SET NAMES UTF8');


$search = array('%','\\','/','"','\'','.','<','>','?','*','(',')',',','#','insert','create','drop','alter','select','from','delete','union','update');


















