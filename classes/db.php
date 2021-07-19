<?php 
$dsn="mysql:dbname=iw31sql;host=localhost;charset=utf8";
$User="watano";
$Pass="hogehoge";

try{
			$pdo=new PDO($dsn,$User,$Pass);
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

}catch(PDOException $e){
	echo $e;
	$_SESSION["errorMsg"]="DB接続失敗";
}

