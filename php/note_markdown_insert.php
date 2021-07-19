<?php
session_start();

require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/notes.class.php";

if($_SERVER["REQUEST_METHOD"] === 'GET' && isset($_SESSION['loginAccount'])) {
    //値を受け取る
    $n_title = htmlspecialchars($_GET['md-heading-editor']);
    $n_body = $_GET['edit'];
    $a_no = $_SESSION['loginAccount'];

    $subject_no = '';
    if(isset($subject_no)) {
        $subject_no = $_GET['subject_no'];
    }

    
    //トリム
    $n_title = trim($n_title);
    $n_body = trim($n_body);

    $validationMsgs = [];
    if(strlen($n_title) == 0) {
        $validationMsgs[] = 'タイトルが未入力です。';
    }
    if(strlen($n_body) == 0) {
        $validationMsgs[] = '本文が未入力です。';
    }

    //時間の取得
    $created = date('Y-m-d H:i:s');

    //オブジェクト生成＋セットする
    $notes = new Notes();
    $notes->setTitle($n_title);
    $notes->setBody($n_body);
    $notes->setNo($a_no);
    $notes->setSubjectNo($subject_no);

     if(!empty($validationMsgs)){
        $_SESSION["validationMsgs"]=$validationMsgs;

        $_SESSION["notes"] = serialize($notes);
        header("Location: ../note_markdown.php");
        die();
    }
    

    //DB処理
    try{
        $sql = "insert into notes (n_title, n_body, created, a_no, subject_no,is_enabled,share) values (:n_title, :n_body, :created, :a_no, :subject_no, :is_enabled, :share)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(array(':n_title' => $notes->getTitle(),
                             ':n_body' => $notes->getBody(),
                             ':created' => $created,
                             ':a_no' => $notes->getNo(),
                             ':subject_no' => $notes->getSubjectNo(),
                             'is_enabled' => true,
                             'share' => false
                         ));
        header("Location:../mypage.php");

    }catch(PDOException $e){
        echo $e;
        $_SESSION["errorMsg"]="insertが失敗しました。";
    }finally{
        $pdo=null;
    }
} else {
    $_SESSION["errorMsg"]="不正なアクセスがありました。";

    if(isset($_SESSION["errorMsg"])){
        header("Location:../error.php");
        die();
    }
}
