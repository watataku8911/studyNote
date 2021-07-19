<?php
session_start();

require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/Account.class.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/notes.class.php";



if(!isset($_SESSION["loginAccount"]) && $_SERVER["REQUEST_METHOD"] === "GET") {
	$_SESSION["errorMsg"]="不正なアクセスがありました。またはログインしていないか、前回ログインしてから一定時間が経過しています。もう一度ログインしなおしてください。";

	if(isset($_SESSION["errorMsg"])){
		header("Location:../error.php");
		die();
	}
} else {
	
	$deleteDay = $_POST["deleteDay"];//削除感覚を受け取る。
	$deletes = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * $deleteDay);//削除される日付。
	

	$account = new Account();
	$account->setDeleteDay($deleteDay);

	$notes = new Notes();
	$notes->setDeletes($deletes);


	try {
		$sqlAccount = "update accounts set delete_day=:delete_day where a_no=:a_no";
		$sqlNotes = "update notes set deletes=:deletes where a_no=:a_no and is_enabled=false";
		$stmtAccount = $pdo->prepare($sqlAccount);
		$stmtNotes = $pdo->prepare($sqlNotes);
		$resultAccount = $stmtAccount->execute(array(':delete_day' => $account->getDeleteDay(), ':a_no' => $_SESSION["loginAccount"]));
		$resultNotes = $stmtNotes->execute(array(':deletes' => $notes->getDeletes(), ':a_no' => $_SESSION["loginAccount"]));
		if($resultAccount && $resultNotes) {
			header('Location:../deleteNotes.php');
		}
	} catch(PDOException $e) {
		echo $e;
		$_SESSION["errorMsg"] = "deleteが失敗しました";
	} finally {
		$pdo = null;
	}
}