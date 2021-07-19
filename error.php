<?php
session_start();

$errorMsg="もう一度やり直してください";
if(isset($_SESSION["errorMsg"])){
	$errorMsg=$_SESSION["errorMsg"];
}

session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Error</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<link rel="stylesheet" type="text/css" href="css/error.css">
</head>
<body>
<header>
<h1>Error</h1>
</header>
	<section>
		<h2>申し訳ございません。障害が発生しました</h2>
		<p>
			以下のメッセージをご確認ください<br>
			<?= $errorMsg ?>
		</p>
	</section>
	<p id="sendTop"><a href="index_sub.php">TOPへ戻る</a></p>
	<div id="image"></div>
</body>
</html>