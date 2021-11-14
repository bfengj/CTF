<?php

error_reporting(0);
session_start();

include_once "config.php";
include_once "flag.php";

function init_status() {
    if (!isset($_SESSION['floor'])) {
        $_SESSION['floor'] = 1;
    }
    if (!isset($_SESSION['disabled_functions'])) {
        $_SESSION['disabled_functions'] = array();
    }
    if (!isset($_SESSION['k'])) {
        $_SESSION['k'] = random_str(8);
    }
}

function reset_status() {
    $_SESSION['floor'] = 1;
    $_SESSION['disabled_functions'] = array();
    $_SESSION['k'] = random_str(8);
}

function random_str($length=0) {
    $ss = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    if ($length == 0) {
        $length = mt_rand(10, 20);
    }
    $res = "";
    for($i=0; $i<$length; $i++) {
        $res .= $ss[mt_rand(0, 61)];
    }
    return $res;
}


function random_str2() {
    $ss = "0123456789";
    $length = mt_rand(10, 17);
    $res = "";
    $res .= $ss[mt_rand(1, 9)];
    for($i=0; $i<$length; $i++) {
        $res .= $ss[mt_rand(0, 9)];
    }
    return $res;
}


function extract_function($query) {
    global $mysql_functions;
    $allowed_functions = array("concat", "unhex");
    $funcs = array();
    $query_arr = preg_split("/[^a-zA-Z0-9_]/", strtolower($query));
    foreach($mysql_functions as $func) {
        $func = strtolower($func);
        if(!in_array($func, $allowed_functions) && in_array($func, $query_arr)) {
            array_push($funcs, $func);
        }
    }
    return $funcs;
}

init_status();

$max_floor = 0x2e;
if ($_SESSION['floor'] > $max_floor) {
    printf("Congratulations! and flag is %s\n<br>\n", $flag);
    exit(0);
}

if (isset($_GET['reset'])) {
    reset_status();
    printf("Reset successfully!");
    exit(0);
}


printf("You are on floor %d.\n<br>\n", $_SESSION['floor']);
printf("Your key is %s\n<br>\n", $_SESSION['k']);
printf("Disabled Functions: %s\n<br>\n", implode(", ", $_SESSION['disabled_functions']));


if (!isset($_REQUEST['sql'])) {
    highlight_file(__FILE__);
    exit(0);
}

$query = base64_decode($_REQUEST['sql']);
if (empty($query) || strlen($query) > 0x100 || !ctype_print($query)) {
    die("Die");
}

$disabled_functions = $_SESSION['disabled_functions'];
foreach ($disabled_functions as $func) {
    if (stripos($query, $func) !== false) {
        die("Die");
    }
}

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
$mysqli = new mysqli($db_host, $db_user2, $db_password2, $db_name);

$k = $_SESSION['k'];
$v = random_str2();

if (!$conn->query("DELETE FROM `s` WHERE k='$k';")) {
    die("Die");
}
if (!$conn->query("INSERT INTO `s` (`k`, `v`) values ('$k', '$v');")) {
    die("Die");
}

if ($mysqli->query($query) === false) {
    $err_msg = mysqli_error($mysqli);
    if (!empty($err_msg) && (strpos($err_msg, $v) !== false)) {
        $funcs = extract_function($query);
        if (empty($funcs)) {
            die("Die");
        }
        foreach($funcs as $func) {
            if (in_array($func, $disabled_functions)) {
                die("Die");
            }
        }
        $_SESSION['floor'] += 1;
        $_SESSION['disabled_functions'] = array_merge($_SESSION['disabled_functions'], $funcs);

        if ($_SESSION['floor'] > $max_floor) {
            printf("Congratulations! and flag is %s\n<br>\n", $flag);
        } else {
            printf("Success! You are on floor %d, and your key is %s.\n<br>\n", $_SESSION['floor'], $_SESSION['k']);
            printf("Disabled Functions: %s\n<br>\n", implode(", ", $_SESSION['disabled_functions']));
        }
        exit(0);
    }
}
die("Die");
?>
