<?php
define('ROOT_PATH', dirname(__FILE__));

$log_type = @$_GET['log_type'];
if(!isset($log_type)){
    $log_type = "look";
}

$gets = http_build_query($_REQUEST);

$real_ip = $_SERVER['REMOTE_ADDR'];
$log_ip_dir = ROOT_PATH . '/log/' . $real_ip;

if(!is_dir($log_ip_dir)){
    mkdir($log_ip_dir, 0777, true);
}

$log = 'Time: ' . date('Y-m-d H:i:s') . ' IP: [' . @$_SERVER['HTTP_X_FORWARDED_FOR'] . '], REQUEST: [' . $gets . '], CONTENT: [' . file_get_contents('php://input') . "]\n";
$log_file = $log_ip_dir . '/' . $log_type . '_www.log';

file_put_contents($log_file, $log, FILE_APPEND);

?>