<?php
session_start();

require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/noteImages.class.php";

if($_SERVER["REQUEST_METHOD"] === "POST") {
	$image = $_FILES["image"]["name"];//imageを受け取る

	$noteImages = new noteImages();
	$noteImages->setImageName($image);
	$noteImages->setNo($_SESSION["loginAccount"]);


	$validationMsg = [];
	if(strlen($image) == 0) {
		$validationMsg[] = '画像を選択してください';
	} else {
		//アップされた画像の拡張子を抜き出す
		$ext=substr($image,-3);
		//拡張子を調べて画像のアップ
		if($ext!="jpg" && $ext!="gif" && $ext!="png"){
			$validationMsg[]="画像ファイルのみアップできます";
		} else {
			move_uploaded_file($_FILES["image"]["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/IW31/note_image/".$_FILES["image"]["name"]);

			$sql = 'insert into note_images (image_name,a_no) value (:image_name,:a_no)';
			$stmt = $pdo->prepare($sql);
			$result = $stmt->execute(array(":image_name" => $noteImages->getImageName(), ":a_no" => $noteImages->getNo()));
			if($result) {
				header("Location:../note_markdown.php");
			}
		}
	}


	if(!empty($validationMsg)){
		$_SESSION["validationMsg"]=$validationMsg;

		header("Location:../note_markdown.php");
		die();
	}


}
