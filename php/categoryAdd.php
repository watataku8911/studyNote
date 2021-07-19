<?php
session_start();

require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/subject.class.php";
if(isset($_SESSION["loginAccount"])) {
	$categoryName = htmlspecialchars($_POST["categoryName"]);
	$validationMsg = [];
	if(strlen($categoryName) == 0){
        $validationMsg[] = 'カテゴリーを入力してください。';
    }

	$subjects = new Subject();
	$subjects->setSubjectName($categoryName);


	if(!empty($validationMsg)){
        $_SESSION["validationMsg"]=$validationMsg;

        header("Location: ../mypage.php");
        die();
    }

	try{
		$sql = "insert into subjects (subject_name,a_no) value (:subject_name,:a_no)";
		$stmt = $pdo->prepare($sql);
		$result = $stmt->execute(array(":subject_name" => $subjects->getSubjectName(), ":a_no" => $_SESSION["loginAccount"]));
		if($result){
			$_SESSION["category"] = 'カテゴライズを登録しました。';
			header("Location: ../mypage.php");
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
} else {
	$_SESSION["errorMsg"]="不正なアクセスがありました。";

	if(isset($_SESSION["errorMsg"])){
		header("Location:../error.php");
		die();
	}
}