<?php
session_start();
if($_SERVER["REQUEST_METHOD"]==="POST"){
	$userName=htmlspecialchars($_POST["userName"]);
	$validationMsg=[];
	$image = "";
	if(!empty($image)){
		
		$image = $_FILES["image"]["name"];
		//アップされた画像の拡張子を抜き出す
		$ext=substr($image,-3);
		//拡張子を調べて画像のアップ
		if($ext!="jpg" && $ext!="gif" && $ext!="png"){
			$validationMsg[]="画像ファイルのみアップできます";
		} else {
			move_uploaded_file($_FILES["image"]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/IW31/data/".$_FILES["image"]["name"]);
		}
	} else {
		$image = '6706.png';//デフォルトの画像
	}

	
	
	//受け取ったパスワードをハッシュ化
	$pass=$_POST["pass"];
	$share_pass=$_POST["share_pass"];



	$userName=trim($userName);
	$pass=trim($pass);
	$share_pass=trim($share_pass);

	if(strlen($userName)==0){
		$validationMsg[]="アカウント名は必須入力です。";
	}
	
	if(strlen($pass)==0){
		$validationMsg[]="ログインパスワードは必須入力です。";
	}

	if(strlen($share_pass)==0){
		$validationMsg[]="共有パスワードは必須入力です。";
	}

	if($pass == $share_pass) {
		$validationMsg[] ="ログインパスワードと共有パスワードは同じではいけません。";
	}



	$hash=password_hash($pass,PASSWORD_DEFAULT);
	$share_hash=password_hash($share_pass,PASSWORD_DEFAULT);


	if(!empty($validationMsg)){
		$_SESSION["validationMsg"]=$validationMsg;

		$_SESSION["userName"]=$userName;
		header("Location:../index_sub.php");
		die();
	}


	require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
	require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/Account.class.php";
	require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/passward.class.php";

	$account=new Account();
	$account->setUserName($userName);
	$account->setImage($image);
	$account->setDeleteDay(3);

	$password=new Password();
	$password->setHash($hash);
	$password->setShareHash($share_hash);

	try{
		$sqlaccount="insert into accounts (a_name,a_image,delete_day) value (:a_name,:a_image,:delete_day)";
		$sqlpassword="insert into password (p_pass,share_pass) value (:p_pass,:share_pass)";
		$stmt=$pdo->prepare($sqlaccount);
		$stmt->execute(array(":a_name"=>$account->getUserName(),
							 ":a_image"=>$account->getImage(),
							 ":delete_day"=>$account->getDeleteDay()
							 ));
		$stmt=$pdo->prepare($sqlpassword);
		$stmt->execute(array(":p_pass"=>$password->getHash(), ":share_pass"=>$password->getShareHash()));

		$sqlSelect="select max(a_no) from accounts";
		$stmt=$pdo->query($sqlSelect);
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$a_no=$row["max(a_no)"];
			$_SESSION["insertAccount"]=$a_no;
			header("Location:../index_sub.php");
		}
	}catch(PDOException $e){
		echo $e;
		$_SESSION["errorMsg"]="insertが失敗しました";
	}finally{
		$pdo=null;
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