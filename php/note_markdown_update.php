<?php
session_start();

if($_SERVER["REQUEST_METHOD"]==="POST"){
	$n_no=$_POST["n_no"];
	$n_title=htmlspecialchars($_POST["md-heading-editor"]);
	$n_body=$_POST["edit"];
	$subject_no=$_POST["subject_no"];

	$n_title=trim($n_title);
	$n_body=trim($n_body);


	require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
	require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/notes.class.php";

	$validationMsgs = [];
    
    if(strlen($n_title) == 0) {
        $validationMsgs[] = 'タイトルが未入力です。';
    }
    if(strlen($n_body) == 0) {
        $validationMsgs[] = '本文が未入力です。';
    }

	$notes=new Notes();
	$notes->setN_NO($n_no);
	$notes->setTitle($n_title);
	$notes->setBody($n_body);
	$notes->setSubjectNo($subject_no);

	if(!empty($validationMsgs)){
        $_SESSION["validationMsgs"]=$validationMsgs;

        $_SESSION["notes"] = serialize($notes);
        header("Location: ../note_markdown.php");
        die();
    }



	try{
		$sql="update notes set n_title=:n_title,n_body=:n_body,subject_no=:subject_no where n_no=:n_no";
		$stmt=$pdo->prepare($sql);
		$stmt->execute(array(":n_title"=>$notes->getTitle(),
							 ":n_body"=>$notes->getBody(),
							 ":subject_no"=>$notes->getSubjectNo(),
							 ":n_no"=>$notes->getN_No()
						));
		$_SESSION["category"] = 'ノートを編集しました';
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

}else{
 	$_SESSION["errorMsg"]="不正なアクセスがありました。";

 	if(isset($_SESSION["errorMsg"])){
		header("Location:../error.php");
		die();
	}
}		