<?php
if (count($_POST) > 0) {
  while (true) {
    if ($_POST['captcha'] != $_SESSION['captcha']) {
      $message = 'Captcha error!';
      break;
    }
    $url = $_POST['url'] ?? '';
    file_get_contents('http://bot:3000/query/' . base64_encode($url));
    $message = 'Admin will view your post soon.';
    break;
  }
}
$_SESSION['captcha'] = random_int(1, 100000);

?>
<!doctype html>
<html>
<head>
  <?php require './includes/header-tags.php';?>
  <title></title>
</head>
<body>

  <div class="ui container">
    <form method="post">
      <div class="ui form">
        <div class="ui error message" style="<?php echo $message === '' ? '' : 'display: block';?>"><?php echo $message;?></div>
        <div class="field">
          <label>Post URL</label>
          <input type="text" id="url" name="url" placeholder="http://172.28.1.120/"/>
        </div>
        <div class="field">
          <label>Captcha: substr(md5(captcha), 0, 6) == "<?php echo substr(md5($_SESSION['captcha']), 0, 6);?>"</label>
          <input type="text" id="captcha" name="captcha" />
        </div>
        <div class="field">
          <button class="ui button submit" type="submit">Submit</button>
        </div>
      </div>
    </form>
  </div>

</body>

</html>

