<?php
session_start();

require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";

if(!isset($_SESSION['loginAccount'])) {
	$_SESSION["errorMsg"]="不正なアクセスがありました。";

    if(isset($_SESSION["errorMsg"])){
        header("Location:error.php");
        die();
    }
} else {
	$n_no = $_GET["n_no"];
	$deleted = date('Y-m-d H:i:s');

	try{
		$sqlAccount = 'select delete_day from accounts where a_no=:a_no';
		$stmtAccount = $pdo->prepare($sqlAccount);
		$stmtAccount->execute(array(":a_no" => $_SESSION["loginAccount"]));
		if($row = $stmtAccount->fetch(PDO::FETCH_ASSOC)) {
			$deleteDay = $row["delete_day"];
			$deletes = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * $deleteDay);//削除される日付。
			$sql = "update notes set is_enabled=:is_enabled,deleted=:deleted, deletes=:deletes where n_no=:n_no";
			$stmt = $pdo->prepare($sql);
			$result = $stmt->execute(array(":is_enabled" => false, ":deleted" => $deleted, ":deletes" => $deletes, ":n_no" => $n_no));
			if($result) {
				header("Location: ../mypage.php");
			}
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

}