<?php
/*
 * 参数说明
 * $max_file_size : 上传文件大小限制, 单位BYTE
 * $destination_folder : 上传文件路径
 * $watermark : 是否附加水印(1为加水印,其他为不加水印);
 * 使用说明:
 * 1. 将PHP.INI文件里面的"extension=php_gd2.dll"一行前面的;号去掉,因为我们要用到GD库;
 * 2. 将extension_dir =改为你的php_gd2.dll所在目录;
 */
// 上传文件类型列表
$uptypes = array (
    'image/jpg',
    'image/png',
    'image/jpeg',
    'image/pjpeg',
    'image/gif',
    'image/bmp',
    'image/x-png'
);
$max_file_size = 20000000;              //上传文件大小限制，单位BYTE
$destination_folder = 'upload/';     //上传文件路径
$watermark = 0;                         //是否附加水印(1为加水印,其他为不加水印);
$watertype = 1;                         //水印类型(1为文字,2为图片)
$waterposition = 1;                     //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
$waterstring = "xxxx.com/"; //水印字符串
$waterimg = "xplore.gif";                //水印图片
$imgpreview = 1;                         //是否生成预览图(1为生成,其他为不生成);
$imgpreviewsize = 1 / 2;                 //缩略图比例
?>