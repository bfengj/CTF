<?hh
<<__EntryPoint>>
function main(): void {
	$sandbox = '/var/www/html/sandbox/' . md5($_SERVER['REMOTE_ADDR']) . '/';
	@mkdir($sandbox);
	$s = file_get_contents(__FILE__);
	ini_set('open_basedir', $sandbox);
	if (!isset($_GET['url'])) {
		echo $s;
		echo "\n\n";
		echo "// Your sandbox: $sandbox";
	} else {
		$url = $_GET['url'] ?? 'http://127.0.0.1/hello.html';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		echo $output;
	}
}
// Hint:
// Do not use any scanner / 不要使用任何扫描器
// No internal network / 没有内网
