<?php
session_start();

if(isset($_SESSION["loginAccount"])) {
	require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
	require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/Account.class.php";
	require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/notes.class.php";
	require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/subject.class.php";

	$validationMsg=null;
	if(isset($_SESSION["validationMsg"])){
		$validationMsg=$_SESSION["validationMsg"];
	}
	$userName="";
	if(isset($_SESSION["userName"])){
		$userName=$_SESSION["userName"];
	}

	unset($_SESSION["validationMsg"]);

	$noteList=[];
	$subjectList=[];
	try{
		$sqlSubject="select * from subjects";
		$stmt=$pdo->query($sqlSubject);
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$subjectNo=$row["subject_no"];
			$subjectName=$row["subject_name"];
			$subjects=new Subject();
			$subjects->setSubjectNo($subjectNo);
			$subjects->setSubjectName($subjectName);

			$subjectList[$subjectNo]=$subjects;
		}

		$sql="select * from accounts where a_no=:a_no";
		$stmt=$pdo->prepare($sql);
		$stmt->execute(array(":a_no"=>$_SESSION["loginAccount"]));
		if($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$a_no = $row["a_no"];
			$userName = $row["a_name"];
			$image = $row["a_image"];
			$deleteDay = $row["delete_day"];

			$account = new Account();
			$account->setNo($a_no);
			$account->setUserName($userName);
			$account->setImage($image);
			$account->setDeleteDay($deleteDay);
		}

		$sql = "select * from notes where a_no=:a_no and is_enabled=false order by deleted desc";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(":a_no" => $account->getNo()));
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			$n_no=$row["n_no"];
			$n_title=$row["n_title"];
			$n_body=$row["n_body"];
			$deleted=$row["deleted"];

			$notes=new Notes();
			$notes->setN_No($n_no);
			$notes->setTitle($n_title);
			$notes->setBody($n_body);
			$notes->setDeleted($deleted);

			$noteList[$n_no]=$notes;

		}
		
		$sql = "delete from notes where deletes<:deletes";
		$stmt = $pdo->prepare($sql);
			$stmt->execute(array(":deletes" => date('Y-m-d H:i:j')));
	}catch(PDOException $e){
		echo $e;
		$_SESSION["errorMsg"]="insertが失敗しました";
	}finally{
		$pdo=null;
	}

	if(isset($_SESSION["errorMsg"])){
		header("Location:error.php");
		die();
	}

	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>ゴミ箱</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/dust.css">
		<!--BootStrap_CSS-->
        <link rel="stylesheet" href="css/bootstrap_css/bootstrap.css">
        <link rel="stylesheet" href="css/bootstrap_css/bootstrap.min.css">
        <!--BootStrap_CSS-->

		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/deleteNotes.js"></script>
		<!--BootStrap_JS-->
	    <script src="js/bootstarp_js/bootstrap.min.js"></script>
	    <script src="js/bootstarp_js/bootstrap.js"></script>
	    <!--BootStrap_JS-->


	</head>
	<body>
	<header>
	<div id="deleteDust">
		<input type="button" value="ゴミ箱を空にする" onclick="push()">
	</div>
	<h1>Study&nbsp;Note</h1>
	<div class="btn-group" role="group">
        <button type="button" class="btn btn-default"><a href="mypage.php">マイページ</a></button>
        <button type="button" class="btn btn-default"><a href="logout.php">ログアウト</a></button>
    </div>
	</header>

	<div id="contents">
		<div id="right">
			<h2>アカウント情報</h2>
				<form action="php/deleteDay.php" method="post" id="submit">
					<select name="deleteDay" id="delete">
						<?php
						for($i=1;$i<=7;$i++) {
							if($i == $account->getDeleteDay()) {
						?>
							<option value="<?= $i ?>" selected><?= $i ?></option>
						<?php
							} else {
						?>
							<option value="<?= $i ?>" ><?= $i ?></option>
						<?php
							}
						}
						?>
					</select>
					日後
				</form>
		<table id="table">
				<tr>
					<th><img src="data/<?= $account->getImage() ?>" width="200" height="200"></th>
					<td>
						<?= $account->getUserName() ?>
						<p>@0000-0000-0000-<?= $account->getNo() ?></p>
					</td>
				</tr>
		</table>
		<!--ページトップ-->
		<p id="back-top"><a href="deleteNotes.php"><img src="images/pagetop.png" alt="ページトップ" width="70" height="80"></a></p>
		</div><!--right-->


		<div id="left">
			<h2>ノート一覧</h2>
			<p id="left_message">
				※ゴミ箱に入っているものは削除時間の<span><?= $account->getDeleteDay() ?>日後</span>に削除されます。
			</p>
		<?php
		if($_SERVER["REQUEST_METHOD"]==="GET"){
			foreach($noteList as $notes){
				$body=mb_strimwidth($notes->getBody(), 0, 90,"................................................");
		?>
				<table id="con">
					<tr>
						<th><a href="note_update_new.php?n_no=<?= $notes->getN_No() ?>"><?= $notes->getTitle() ?>(全文を読む)</a></th>
					</tr>
					<tr>
						<td><?= $body ?><a href="php/removeNote.php?n_no=<?= $notes->getN_No() ?>">ゴミ箱から取り出す</a></td>
					</tr>
				</table>
		<?php	
			}
		}	
		?>
		</div><!--left-->
	</div><!--content-->
	</body>
	</html>
<?php
}else{
	$_SESSION["errorMsg"]="不正なアクセスがありました。またはログインしていないか、前回ログインしてから一定時間が経過しています。もう一度ログインしなおしてください。";

	if(isset($_SESSION["errorMsg"])){
		header("Location:error.php");
		die();
	}
}
