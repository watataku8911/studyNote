<?php
session_start();

require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/Account.class.php";


if($_SERVER["REQUEST_METHOD"]==="GET"){
	$_SESSION["errorMsg"]="不正なアクセスがありました。";

 	if(isset($_SESSION["errorMsg"])){
		header("Location:../error.php");
		die();
	}
}else{
	$userName=htmlspecialchars($_POST["userName"]);
	$userName=trim($userName);
	$image= $_FILES["image"]["name"];
	$validationMsg=[];
	if(strlen($image) != 0){
		//アップされた画像の拡張子を抜き出す
		$ext=substr($image,-3);
		//拡張子を調べて画像のアップ
        if($ext!="jpg" && $ext!="gif" && $ext!="png"){
            $validationMsg[]="画像ファイルのみアップできます";
        } else {
	        move_uploaded_file($_FILES["image"]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/IW31/data/".$_FILES["image"]["name"]);
		}
		
	} else {
		$sql = 'select * from accounts where a_no=:a_no';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(':a_no' => $_SESSION["loginAccount"]));
		if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$image = $row['a_image'];

			$accounts = new Account();
			$accounts->setImage($image);
		}
		$image = $accounts->getImage();
	}
	

	
	if(strlen($userName)==0){
		$validationMsg[]="アカウント名は必須入力です。";
	}

	if(!empty($validationMsg)){
		$_SESSION["validationMsg"]=$validationMsg;

		$_SESSION["userName"]=$userName;
		header("Location:../mypage.php");
		die();
	}

	
	$account=new Account();
	$account->setUserName($userName);
	$account->setImage($image);

	try{
		$sql="update accounts set a_name=:a_name,a_image=:a_image where a_no=:a_no";
		$stmt=$pdo->prepare($sql);
		$stmt->execute(array(":a_name"=>$account->getUserName(),
							 ":a_image"=>$account->getImage(),
							 "a_no"=>$_SESSION["loginAccount"]
							 ));
		$_SESSION["category"] = 'アカウントを編集しました。';
		header("Location:../mypage.php");

	}catch(PDOException $e){
		echo $e;
		$_SESSION["errorMsg"]="updateが失敗しました";
	}finally{
		$pdo=null;
	}

	if(isset($_SESSION["errorMsg"])){
		header("Location:../error.php");
		die();
	}


}