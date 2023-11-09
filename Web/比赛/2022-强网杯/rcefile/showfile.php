
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <!-- css -->
    <style>

        * {

            margin: 0;

            padding: 0;
        }
        html, body {
            width: 100%;
            height: 100%;
        }
        body {
            background-image: url("pinkkk.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
</head>

<?php
include 'config.inc.php';

foreach ($userfile as $file){
    $file=e($file);
    echo "<li><a href=\"./{$file}\" target=\"_blank\">" . $file . "</a></li>\n";
}
?>


