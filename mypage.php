<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"] . "/IW31/classes/db.php";
require $_SERVER["DOCUMENT_ROOT"] . "/IW31/classes/Account.class.php";
require $_SERVER["DOCUMENT_ROOT"] . "/IW31/classes/notes.class.php";
require $_SERVER["DOCUMENT_ROOT"] . "/IW31/classes/subject.class.php";

if (isset($_SESSION["loginAccount"])) {

    $validationMsg = null;
    if (isset($_SESSION["validationMsg"])) {
        $validationMsg = $_SESSION["validationMsg"];
    }
    $validationMsgs = null;
    if (isset($_SESSION["validationMsgs"])) {
        $validationMsgs = $_SESSION["validationMsgs"];
    }
    $userName = "";
    if (isset($_SESSION["userName"])) {
        $userName = $_SESSION["userName"];
    }
    if (isset($_SESSION["category"])) {
        $category = $_SESSION["category"];
    }

    unset($_SESSION["validationMsg"]);
    unset($_SESSION["validationMsgs"]);
    unset($_SESSION["category"]);


    $noteList = [];
    $noteLists = [];
    $subjectList = [];
    try {
        $sqlSubject = "select * from subjects where a_no=:a_no order by subject_no desc";
        $stmt = $pdo->prepare($sqlSubject);
        $stmt->execute(array(":a_no" => $_SESSION["loginAccount"]));
        $flg = true;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $flg = false;
            $subjectNo = $row["subject_no"];
            $subjectName = $row["subject_name"];

            $subjects = new Subject();
            $subjects->setSubjectNo($subjectNo);
            $subjects->setSubjectName($subjectName);

            $subjectList[$subjectNo] = $subjects;
        }

        if ($flg) {
            $Msg = 'タグを追加してください。';
        } else {
            $Msg = '新規登録の際に選択したタグで検索できます。';
        }

        $sql = "select * from accounts where a_no=:a_no";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":a_no" => $_SESSION["loginAccount"]));
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $a_no = $row["a_no"];
            $userName = $row["a_name"];
            $image = $row["a_image"];

            $account = new Account();
            $account->setNo($a_no);
            $account->setUserName($userName);
            $account->setImage($image);
        }

        $sql = "select * from notes where a_no=:a_no and is_enabled=true order by created desc";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":a_no" => $account->getNo()));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $n_no = $row["n_no"];
            $n_title = $row["n_title"];
            $n_body = $row["n_body"];
            $share = $row["share"];
            $deleted = $row["deleted"];

            $notes = new Notes();
            $notes->setN_No($n_no);
            $notes->setTitle($n_title);
            $notes->setBody($n_body);
            $notes->setShare($share);

            $noteList[$n_no] = $notes;

        }
        $count = 0;
        $sqlCount = 'select count(deleted) from notes where a_no=:a_no';
        $stmt = $pdo->prepare($sqlCount);
        $stmt->execute(array(":a_no" => $_SESSION["loginAccount"]));
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $count = $row["count(deleted)"];
        }
        $sqlNote = 'select n_no,n_title,deletes from notes where is_enabled=false and a_no=:a_no';
        $stmtNote = $pdo->prepare($sqlNote);
        $stmtNote->execute(array(":a_no" => $_SESSION["loginAccount"]));
        while ($row = $stmtNote->fetch(PDO::FETCH_ASSOC)) {
            $n_no = $row["n_no"];
            $n_title = $row["n_title"];
            $deletes = $row["deletes"];

            $notesSecond = new Notes();
            $notesSecond->setN_No($n_no);
            $notesSecond->setTitle($n_title);
            $notesSecond->setDeletes($deletes);

            $noteLists[$n_no] = $notesSecond;
        }

        $sql = "delete from notes where deletes<:deletes";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":deletes" => date('Y-m-d H:i:j')));

    } catch (PDOException $e) {
        echo $e;
        $_SESSION["errorMsg"] = "insertが失敗しました";
    } finally {
        $pdo = null;
    }

    if (isset($_SESSION["errorMsg"])) {
        header("Location:error.php");
        die();
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>マイページ</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">

        <!--BootStrap_CSS-->
        <link rel="stylesheet" href="css/bootstrap_css/bootstrap.css">
        <link rel="stylesheet" href="css/bootstrap_css/bootstrap.min.css">
        <!--BootStrap_CSS-->

        <link rel="stylesheet" type="text/css" href="css/mypage.css">

    </head>
    <body>
    <header>
        <h1>Study&nbsp;Note</h1>
            <div class="col-sm-8">
                <form action="mypage.php" method="post" id="submit_form">
                        <select name='subject_no' class="form-control" id="usage2select1">
                            <option value=""><?= $Msg ?></option>
                            <option value="0">全て</option>
                            <?php
                            foreach ($subjectList as $subjects) {
                                ?>
                                <option value="<?= $subjects->getSubjectNo() ?>"><?= $subjects->getSubjectName() ?></option>
                                <?php
                            }
                            ?>
                        </select>
                </form>
            </div>
        
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-default"><a data-target="con2" class="modal-open"><img src="images/category.png" width="30" height="30" alt="タグ追加"></a></button>
            <button type="button" class="btn btn-default"><a data-target="con3" class="modal-open">共有</a></button>
            <button type="button" class="btn btn-default"><a href="note_markdown.php">新規作成</a></button>
            <button type="button" class="btn btn-default"><a href="logout.php">ログアウト</a></button>
        </div>
    </header>

    <div id="contents">
        <div id="right">
            <h2>アカウント情報</h2>
<?php
            //バリデーションのメッセージ-->
            if (!is_null($validationMsg)) {
?>
                <div id="message">
                    <pre>＜＜以下のmessageを確認してください＞＞</pre>
                    <ul>
<?php
                        foreach ($validationMsg as $msg) {
?>
                            <li><?= $msg ?></li>
<?php
                        }
?>
                    </ul>
                </div>
<?php
            }
            //バリデーションのメッセージ-->
            if (!is_null($validationMsgs)) {
?>
                <div id="message">
                    <pre>＜＜以下のmessageを確認してください＞＞</pre>
                    <ul>
<?php
                        foreach ($validationMsgs as $msg) {
?>
                            <li><?= $msg ?></li>
<?php
                        }
?>
                    </ul>
                </div>
<?php
            }

            if (isset($category)) {
?>
                <div id="messages">
                    <ul>
                        <li><?= $category ?></li>
                    </ul>
                </div>
<?php
            }
?>

            <table id="table">
                <tr>
                    <th><a data-target="con1" class="modal-open"><img src="data/<?= $account->getImage() ?>" width="200" height="200"></a></th>
                    <td>
                        <?= $account->getUserName() ?>
                        <p>@0000-0000-0000-<?= $account->getNo() ?></p>
                        <p id="folder"><a href="photoList.php"><img src="images/icon_6m_192.png" width="50" height="45"></a></p>
                    </td>
                </tr>
            </table>

                <!-- 時計枠 -->
            <div id="clock_frame">
                <!-- 日付部分 -->
                <span id="clock_date"></span>
                <!-- 時刻部分 -->
                <span id="clock_time"></span>
            </div>

            <div class="hako">
                <!--ページトップ-->
                <p id="back-top"><a href="mypage.php"><img src="images/pagetop.png" alt="ページトップ" width="70" height="80"></a>
                </p>

                <?php
                if ($count == 0) {
                    ?>
                    <p id="speakingMessage">削除対象のノートがありません。</p>
                    <?php
                } else {
                    ?>
                    <div id="speakingMessage">
                        <p>削除対象のノートが<strong><?= $count ?>件</strong>あります。</p>
                        <ul>
                            <?php
                            foreach ($noteLists as $notesSecond) {
                                ?>
                                <li>・<?= $notesSecond->getTitle() ?>が<?= $notesSecond->getDeletesStr() ?>に自動削除されます。</li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                }
                ?>
                <!--ゴミ箱-->
                <p id="dust"><a href="deleteNotes.php"><img src="images/icon_119860_256.png" alt="ゴミ箱" width="70" height="80"></a>
                </p>
            </div><!--hako-->
<?php
if(isset($_SESSION["shareAccount"])) {
    if($_SESSION["loginAccount"] != $_SESSION["shareAccount"]) {
        require $_SERVER["DOCUMENT_ROOT"] . "/IW31/classes/db.php";
        $sql = "select * from accounts where a_no=:a_no";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":a_no" => $_SESSION["shareAccount"]));
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $a_no = $row["a_no"];
            $userName = $row["a_name"];
            $image = $row["a_image"];

            $accounts = new Account();
            $accounts->setNo($a_no);
            $accounts->setUserName($userName);
            $accounts->setImage($image);
        }

?>
        <p id="share" style="margin-top: -70px;"><span><?= $accounts->getUserName() ?></span>さんと共有しています</p>
<?php
    } else{
?>
        <p id="share" styles="margin-top: -70px;">共有していません</p>
<?php
    }
} else {
?>
        <p id="share" style="margin-top: -70px;">共有していません</p>
<?php
}
?>
        </div><!--right-->

        <div id="left">
            <h2>ノート一覧</h2>
            <?php
            if(isset($_SESSION["shareAccount"])) {
                $validationMsgs = [];
                if($_SESSION["shareAccount"] != $_SESSION["loginAccount"]) {
                    $sqlNoteShare = "select * from notes where is_enabled=true and a_no=:a_no and share=true order by created desc";
                    $stmtNoteShare = $pdo->prepare($sqlNoteShare);
                    $stmtNoteShare->execute(array(":a_no" => $_SESSION["shareAccount"]));
                    while ($row = $stmtNoteShare->fetch(PDO::FETCH_ASSOC)) {
                        $body = $row["n_body"];
                        $body = mb_strimwidth($body, 0, 90, "................................................................");
                        ?>
                        <table id="con">
                            <tr>
                                <th><a href="note_update_new.php?n_no=<?= $row["n_no"] ?>"><?= $row["n_title"] ?>（共有ノート）</a></th>
                            </tr>
                            <tr>
                                <td><?= $body ?>&nbsp;&nbsp;</td>
                            </tr>
                        </table>
                    <?php
                    }
                } else {
                     echo "<p id='err'>自分自身と共有できません</p>";
                }
            }
            
            if ($_SERVER["REQUEST_METHOD"] === "GET") {
                foreach ($noteList as $notes) {
                    $body = mb_strimwidth($notes->getBody(), 0, 90, "................................................");
                    ?>
                    <table id="con">
                        <tr>
                            <th><a href="note_update_new.php?n_no=<?= $notes->getN_No() ?>"><?= $notes->getTitle() ?>
                                    (全文を読む)</a></th>
                        </tr>
                        <tr>
                            <td>
                                <?= $body ?><a href="php/confirmNotedelete.php?n_no=<?= $notes->getN_No() ?>" data-toggle="tooltip" title="ゴミ箱へ移動する">×</a>
                                <!-- <hr> -->
                                <form action="php/share.php" method="post">
                                    <select name="share">
<?php
if($notes->getShare() == 0) {
?>
    <option value="0" selected>共有しない</option>
    <option value="1">共有する</option>
<?php
} elseif($notes->getShare() == 1) {
?>
    <option value="0">共有しない</option>
    <option value="1" selected>共有する</option>
<?php
} else {
?>
    <option value="0">共有しない</option>
    <option value="1">共有する</option>
<?php
}
?>
                                    </select>
                                    <input type="hidden" name="n_no" value="<?= $notes->getN_NO() ?>">
                                    <input type="submit" value="決定">
                                </form>
                            </td>
                        </tr>

                    </table>
                    <?php
                }
            }
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                require $_SERVER["DOCUMENT_ROOT"] . "/IW31/classes/db.php";

                $subject_no = $_POST["subject_no"];
                //echo $subject_no;

                if ($subject_no == 0) {
                    $sqlNoteAll = "select * from notes where is_enabled=true and a_no=:a_no order by created desc";
                    $stmtNoteAll = $pdo->prepare($sqlNoteAll);
                    $stmtNoteAll->execute(array(":a_no" => $_SESSION["loginAccount"]));
                    while ($row = $stmtNoteAll->fetch(PDO::FETCH_ASSOC)) {
                        $body = $row["n_body"];
                        $body = mb_strimwidth($body, 0, 90, "................................................................");
                        ?>
                        <table id="con">
                            <tr>
                                <th><a href="note_update_new.php?n_no=<?= $row["n_no"] ?>"><?= $row["n_title"] ?>
                                        (全文を読む)</a></th>
                            </tr>
                            <tr>
                                <td>
                                    <?= $body ?>&nbsp;&nbsp;<a href="php/confirmNotedelete.php?n_no=<?= $row["n_no"] ?>" data-toggle="tooltip" title="ゴミ箱へ移動する">×</a>
                                    <form action="php/share.php" method="post">
                                        <select name="share">
<?php
if($row["share"] == 0) {
?>
    <option value="0" selected>共有しない</option>
    <option value="1">共有する</option>
<?php
} elseif($row["share"] == 1) {
?>
    <option value="0">共有しない</option>
    <option value="1" selected>共有する</option>
<?php
} else {
?>
    <option value="0">共有しない</option>
    <option value="1">共有する</option>
<?php
}
?>
                                        </select>
                                        <input type="hidden" name="n_no" value="<?= $n_no ?>">
                                        <input type="submit" value="決定">
                                    </form>
                                </td>
                            </tr>

                        </table>
                        <?php
                    }
                }
                if ($subject_no != 0) {
                    $sqlNoteSearch = "select * from notes where subject_no=$subject_no and is_enabled=true and a_no=:a_no order by created desc";
                    $stmtNoteSearch = $pdo->prepare($sqlNoteSearch);
                    $stmtNoteSearch->execute(array(":a_no" => $_SESSION["loginAccount"]));

                    $flgs = true;
                    while ($row = $stmtNoteSearch->fetch(PDO::FETCH_ASSOC)) {
                        $flgs = false;
                        $body = $row["n_body"];
                        $body = mb_strimwidth($body, 0, 90, ".....................................................");
                        ?>
                        <table id="con">
                            <tr>
                                <th><a href="note_update_new.php?n_no=<?= $row["n_no"] ?>"><?= $row["n_title"] ?>
                                        (全文を読む)</a></th>
                            </tr>
                            <tr>
                                <td>
                                    <?= $body ?><a href="<?= $row["n_no"] ?>" data-toggle="tooltip" title="ゴミ箱へ移動する">×</a>
                                    <form action="php/share.php">
                                        <select name="share" method="post">
<?php
if($row["share"] == 0) {
?>
    <option value="0" selected>共有しない</option>
    <option value="1">共有する</option>
<?php
} elseif($row["share"] == 1) {
?>
    <option value="0">共有しない</option>
    <option value="1" selected>共有する</option>
<?php
} else {
?>
    <option value="0">共有しない</option>
    <option value="1">共有する</option>
<?php
}
?>
                                        </select>
                                        <input type="hidden" name="n_no" value="<?= $n_no ?>">
                                        <input type="submit" value="決定">
                                    </form>
                                </td>
                            </tr>
                        </table>

                        <?php
                    }
                    if ($flgs) {
                        echo "<div id='a'>";
                        echo "<p>そのカテゴリーのノートが作成されていません。</p>";
                        echo "</div>";
                    }
                }
            }
?>
        </div><!--left-->
    </div><!--comtents-->


    <!--モーダルの中身-->
    <div id="con2" class="modal-content">
        <form action="php/categoryAdd.php" method="post">
            <h2>タグ追加</h2>
                <input type="text" name="categoryName" placeholder="タグ名" class="hoge">
                <input type="submit" value="追加" id="submit_button">
        </form>
    </div>

    <div id="con1" class="modal-content">
        <form action="php/updateAccount.php" method="post" enctype="multipart/form-data">
            <h2>アカウント編集</h2>
            <input type="text" name="userName" class="hoge" placeholder="アカウント名" value="<?= $userName ?>">

            <div id="drag-drop-area">
                <div class="drag-drop-inside">
                    <p class="drag-drop-info">ここにファイルをドロップ</p>
                    <p>または</p>
                    <p class="drag-drop-buttons"><input id="fileInput" type="file" value="ファイルを選択" name="image"></p>
                </div>
            </div>


            <td><input type="submit" value="アカウント編集" id="submit_button"></td>
        </form>
    </div>

    <div id="con3" class="modal-content">
        <form action="php/shareNote.php" method="post" enctype="multipart/form-data">
            <h2>共有したいアカウントのユーザー名とパスワードを入力してください</h2>
            <input type="text" name="user" class="hoge" placeholder="アカウント名" value="watano">

            <input type="password" name="pass" placeholder="共有パスワード" value="share" class="hoge">


            <input type="submit" value="共有" id="submit_button">
        </form>
    </div>
    </body>
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/mypage.js"></script>
    <script src="js/photoAdd.js"></script>
    <!--BootStrap_JS-->
    <script src="js/bootstarp_js/bootstrap.min.js"></script>
    <script src="js/bootstarp_js/bootstrap.js"></script>
    <!--BootStrap_JS-->
    </html>
    <?php
} else {
    $_SESSION["errorMsg"] = "不正なアクセスがありました。またはログインしていないか、前回ログインしてから一定時間が経過しています。もう一度ログインしなおしてください。";

    if (isset($_SESSION["errorMsg"])) {
        header("Location:error.php");
        die();
    }
}
