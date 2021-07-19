<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"] . "/IW31/classes/db.php";

if($_SERVER["REQUEST_METHOD"]==="POST"){
	$share = $_POST["share"];
	$n_no = $_POST["n_no"];
	try{
		$sql = "update notes set share=:share where n_no=:n_no";
		$stmt = $pdo->prepare($sql);
		$result = $stmt->execute(array(':share' => $share, ':n_no' => $n_no));
		if($result){
			header("Location:../mypage.php");
		}
	} catch (PDOException $e) {
        echo $e;
        $_SESSION["errorMsg"] = "updateが失敗しました";
    } finally {
        $pdo = null;
    }

    if (isset($_SESSION["errorMsg"])) {
        header("Location:../error.php");
        die();
    }
	
}