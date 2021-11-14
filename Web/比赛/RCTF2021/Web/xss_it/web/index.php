<?php

session_start();
$action = $_GET['action'] ?? 'index';
$action = preg_replace('/[^a-zA-Z0-9\-\._]/', '', $action);
$action = file_exists("pages/$action.php") ? "pages/$action.php" : "pages/index.php";

require $action;

