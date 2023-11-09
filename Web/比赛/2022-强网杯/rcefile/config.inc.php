
<?php
spl_autoload_register();
error_reporting(0);

function e($str){
    return htmlspecialchars($str);
}
$userfile = empty($_COOKIE["userfile"]) ? [] : unserialize($_COOKIE["userfile"]);
?>
<p>
    <a href="/index.php">Index</a>
    <a href="/showfile.php">files</a>
</p>
