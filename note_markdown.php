<?php
session_start();

require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/subject.class.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/notes.class.php";

$notes = new Notes();
if(isset($_SESSION["notes"])){
    $notes=$_SESSION["notes"];
    $notes=unserialize($notes);
}


$validationMsgs=null;
if(isset($_SESSION["validationMsgs"])){
    $validationMsgs=$_SESSION["validationMsgs"];
}
$validationMsg=null;
if(isset($_SESSION["validationMsg"])){
    $validationMsg=$_SESSION["validationMsg"];
}
unset($_SESSION["validationMsg"]);
unset($_SESSION["validationMsgs"]);
unset($_SESSION["notes"]);

$subjectList=[];
try{
    $sqlSubject="select * from subjects where a_no=:a_no order by subject_no desc";
    $stmt=$pdo->prepare($sqlSubject);
    $stmt->execute(array(":a_no" => $_SESSION["loginAccount"]));

        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            $subjectNo=$row["subject_no"];
            $subjectName=$row["subject_name"];
            $subjects=new Subject();
            $subjects->setSubjectNo($subjectNo);
            $subjects->setSubjectName($subjectName);

            $subjectList[$subjectNo]=$subjects;
        }
}catch(PDOException $e){
    echo $e;
    $_SESSION["errorMsg"]="selectが失敗しました。";
}finally{
    $pdo=null;
}

if(isset($_SESSION["errorMsg"])){
    header("Location:error.php");
    die();
}



?>
<!DOCTYPE html>
<html lang="ja">
<head>
    
    <title>新規作成</title>

    <!--BootStrap_CSS-->
    <link rel="stylesheet" href="css/bootstrap_css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap_css/bootstrap.min.css">
    <!--BootStrap_CSS-->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="css/github.css">
</head>
<body>
<?php
    if(!is_null($validationMsg)){
        echo "<section id='errorMsg'>";
                    echo "<ul>";
                    foreach($validationMsg as $msgs){
                        echo "<li>".$msgs."</li>";
                    }
                    echo "</ul>";
        echo "</section>";
    }
    if(!is_null($validationMsgs)){
        echo "<section id='errorMsg'>";
                    echo "<ul>";
                    foreach($validationMsgs as $msg){
                        echo "<li>".$msg."</li>";
                    }
                    echo "</ul>";
        echo "</section>";
    }
?>

<header>
    <p class="mypage"><a href="mypage.php"><img src="images/btn03-07.png" alt="戻るボタン"></a></p>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default"><a data-target="con1" class="modal-open"><img src="images/icon_6m_192.png" width="38" height="40"></a></button>
        <button type="button" class="btn btn-default"><a href="help.html"><img src="images/help.png" width="30" height="30" id="help"></a></button>
    </div>
<form action="php/note_markdown_insert.php">
    <div class="col-sm-8">
        <select name='subject_no' class="form-control" id="usage2select1">
            <option value="0">ノートにタグがつけれます</option>
            <?php
            foreach ($subjectList as $subjects) {
                ?>
                <option value="<?= $subjects->getSubjectNo() ?>"><?= $subjects->getSubjectName() ?></option>
                <?php
            }
            ?>
        </select>
    </div>
</header>
    <div id="title">
        <input type="text" class="md-heading-editor" name="md-heading-editor" placeholder="タイトルを入力(必須)" value="<?= $notes->getTitle() ?>">
    </div>
<div id="markdown">
    <!--Markdown出力-->
    <textarea id="edit" name="edit" placeholder="マークダウンで記入してください(必須)" onscroll="preview.scrollTop=this.scrollTop"><?= $notes->getBody() ?></textarea>
    <div id="preview" onscroll="edit.scrollTop=this.scrollTop"></div>
    <!--***************-->
</div><!--markdown-->
    <footer>
        <input type="submit" value="保存" id="submit_button">
    </footer>
</form>

<div id="con1" class="modal-content">
    <form action="php/note_imageAdd.php" method="post" enctype="multipart/form-data">
    <h2>画像追加</h2>

        <div id="drag-drop-area">
            <div class="drag-drop-inside">
              <p class="drag-drop-info">ここにファイルをドロップ</p>
              <p>または</p>
              <p class="drag-drop-buttons"><input id="fileInput" type="file" value="ファイルを選択" name="image"></p>
            </div>
        </div>
            <input type="submit" value="追加">
    </form>
</div>





</body>
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/note_markdown.js"></script>
    <script src="js/marked.min.js"></script>
    <script src="js/highlight.pack.js"></script>
    <script src="js/modal.js"></script>

</html>
