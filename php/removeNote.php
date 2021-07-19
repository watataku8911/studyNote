<?php
session_start();

require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";

if(!isset($_SESSION['loginAccount'])) {
	$_SESSION["errorMsg"]="不正なアクセスがありました。";

    if(isset($_SESSION["errorMsg"])){
        header("Location:../error.php");
        die();
    }
} else {
	$n_no = $_GET["n_no"];
	
	try{
		$sql = "update notes set is_enabled=:is_enabled,deleted=:deleted,deletes=:deletes where n_no=:n_no";
		$stmt = $pdo->prepare($sql);
		$result = $stmt->execute(array(":is_enabled" => true, ":deleted" => NULL, ":deletes" => NULL, ":n_no" => $n_no));
		if($result) {
			header("Location: ../deleteNotes.php");
		}
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