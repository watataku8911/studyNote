<?php
session_start();
if($_SERVER["REQUEST_METHOD"]==="POST"){
	

	$user=htmlspecialchars($_POST["user"]);
	$pass=$_POST["pass"];
	

	$user=trim($user);
	$pass=trim($pass);

	$validationMsgs=[];
	if(strlen($user)==0){
		$validationMsgs[]="アカウント名が未入力です。";
	}
	if(strlen($pass)==0){
		$validationMsgs[]="パスワードが未入力です。";

	}
	
	if(!empty($validationMsgs)){
		$_SESSION["validationMsgs"]=$validationMsgs;

		header("Location:../index.php");
		die();
	}

	require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";

	try{
		$sqlPassword="select a_no from accounts where a_name=:a_name";
		$stmt=$pdo->prepare($sqlPassword);
		$stmt->execute(array(":a_name"=>$user));
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$a_no=$row["a_no"];
		}else{
			$validationMsgs[]="a_noの取得に失敗しました。";
			if(!empty($validationMsgs)){
				$_SESSION["validationMsgs"]=$validationMsgs;

				header("Location:../index.php");
				die();
			}
		}

		$sql="select p_pass from password where a_no=:a_no";
		$stmt=$pdo->prepare($sql);
		$stmt->execute(array(":a_no"=>$a_no));
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$p_pass=$row["p_pass"];
		}
		
		if(password_verify($pass,$p_pass)){			
			$_SESSION["loginAccount"]=$a_no;
			header("Location:../mypage.php");
		}else{
			$validationMsgs[]="ここに入ってるログイン失敗しました。";
		}
		
	}catch(PDOException $e){
		echo $e;
		$_SESSION["errorMsg"]="selectが失敗しました";
	}finally{
		$pdo=null;
	}

	if(!empty($validationMsgs)){
		$_SESSION["validationMsgs"]=$validationMsgs;

		header("Location:../index.php");
		die();
	}

	if(isset($_SESSION["errorMsg"])){
		header("Location:../error.php");
		die();
	}
	
}else{
 	$_SESSION["errorMsg"]="不正なアクセスがありました。";

 	if(isset($_SESSION["errorMsg"])){
		header("Location:../error.php");
		die();
	}
 }	