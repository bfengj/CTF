<?php
include "config.inc.php";
?>

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
            background-image: url("pink.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
</head>

<body>
<form action="/upload.php" method="post" enctype="multipart/form-data">
    upload pickture:
    <input type="file" name="file" >
    <input type="submit" value="上传文件">
</form>
</body>
