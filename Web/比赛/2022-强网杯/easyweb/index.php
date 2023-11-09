<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>神奇的照片墙</title>
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
            background-image: url("background.webp");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
        h1 {
            text-align: center;
            font-size: 3em;
            height: 10%;
            background-color: rgba(98,0,101,0.7);
            color: aliceblue;
            padding-top: 30px;
        }
        form {
            position: relative;
            background-color: rgba(0,0,0,0.71);
            top: 40%;
            left: 50%;
            transform: translate(-50% , -50%);
            height: 50%;
            width: 30%;
        }
        input {
            position: relative;
            top: 40%;
            left: 50%;
            transform: translate(-50% , -50%);
            height: 8%;
            width: 30%;
            color: cornsilk;
        }
        input[type="file"] {
            font-size: 1.2em;
            margin-bottom: 3%;
            cursor:pointer;
        }
        input[type="submit"] {
            background-color: coral;
            font-size: 1.2em;
            transition: background-color, 0.3s;
            cursor:pointer;
        }
        input[type="submit"]:hover {
            background-color: crimson;
        }
        a {
            position: relative;
            top: 40%;
            left: 45%;
            transform: translate(-50% , -50%);
            height: 8%;
            width: 30%;
            color: aliceblue;
            text-decoration: none;
            font-size: 1.2em;
            cursor:pointer;
        }
        a:hover {
            text-decoration: underline;
        }
        p.tip {
            text-align: center;
            color: blanchedalmond;
            font-size: 1.5em;
            font-family: Papyrus, serif;
        }
    </style>
</head>

<body>
<h1>欢迎来到强网杯照片墙</h1>

<form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file" id="file"><br>
    <input type="submit" name="submit" value="提交"><br>
    <a href="showfile.php?f=./demo.png">查看照片</a>

    <?php
    $upload = md5("2022qwb".$_SERVER['REMOTE_ADDR']);
    @mkdir($upload, 0333, true);
    if(isset($_POST['submit'])) {
        include 'upload.php';
    }
    ?>

</form>
</body>