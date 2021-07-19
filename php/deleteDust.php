<?php
session_start();

require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/notes.class.php";

if(isset($_SESSION["loginAccount"])){
	try {
		$sql = 'delete from notes where is_enabled=false and a_no=:a_no';
		$stmt = $pdo->prepare($sql);
		$result = $stmt->execute(array(':a_no' => $_SESSION["loginAccount"]));
		if($result) {
			header("Location:../deleteNotes.php");
		}
	} catch(PDOException $e) {
		echo $e;
		$_SESSION["errorMsg"] = "deleteが失敗しました";
	} finally {
		$pdo = null;
	}

	if(isset($_SESSION["errorMsg"])){
		header("Location:../error.php");
		die();
	}
}