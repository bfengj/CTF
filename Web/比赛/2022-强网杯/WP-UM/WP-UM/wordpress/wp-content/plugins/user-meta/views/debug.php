<?php
namespace UserMeta;

/**
 * Debuging
 * admin access control in controller
 */
global $userMeta;

ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "um-debug<br/>";

if (! empty($_GET["option"])) {
    $userMeta->dump($userMeta->getData($_GET["option"]));
}

if (! empty($_GET['phpinfo'])) {
    phpinfo();
}