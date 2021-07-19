<?php
session_start();

if(isset($_SESSION["loginAccount"])){
	

	session_unset();
	session_destroy();
?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>ログアウト</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
	
		<link rel="stylesheet" type="text/css" href="css/logout.css">
		<script type="text/javascript">
			setTimeout(function(){
				window.location.href = 'http://localhost:8888/IW31/index_sub.php';
			},  1000);
		</script>
	</head>
	<body>
		<div id="logout">
			<div id="image"></div>
		</div>	
	</body>
	</html>
<?php
}else{
	$_SESSION["errorMsg"]="不正なアクセスがありました。";

 	if(isset($_SESSION["errorMsg"])){
		header("Location:error.php");
		die();
	}
 }

?>
