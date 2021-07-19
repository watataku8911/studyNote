<?php
session_start();
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/db.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/noteImages.class.php";
require $_SERVER["DOCUMENT_ROOT"]."/IW31/classes/Account.class.php";

if(isset($_SESSION["loginAccount"])) {

	$noteImageList=[];
	try{
		$sql = 'select * from note_images where a_no=:a_no';
		$stmt=$pdo->prepare($sql);
		$stmt->execute(array(":a_no" => $_SESSION["loginAccount"]));
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$image_no = $row["image_no"];
			$image_name = $row["image_name"];
			$a_no = $row["a_no"];

			$noteImage = new noteImages();
			$noteImage->setImageNo($image_no);
			$noteImage->setImageName($image_name);
			$noteImage->setNo($a_no);
			$noteImageList[$image_no] = $noteImage;
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

	}catch(PDOException $e){
		echo $e;
		$_SESSION["errorMsg"]="selectが失敗しました";
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
		<title>フォトリスト</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/photoList.css">
		 <!--BootStrap_CSS-->
        <link rel="stylesheet" href="css/bootstrap_css/bootstrap.css">
        <link rel="stylesheet" href="css/bootstrap_css/bootstrap.min.css">
        <!--BootStrap_CSS-->

        <!--BootStrap_JS-->
	    <script src="js/bootstarp_js/bootstrap.min.js"></script>
	    <script src="js/bootstarp_js/bootstrap.js"></script>
	    <!--BootStrap_JS-->

	</head>
	<body>
	<header>
		<h1>Study&nbsp;Note</h1>
	</header>

	<div id="contents">
		<div id="left">
			<h2>フォトリスト</h2>
			<div id="box">
	<?php
		foreach ($noteImageList as $noteImage) {
	?>
			<img src="note_image/<?= $noteImage->getImageName() ?>" alt="<?= $noteImage->getImageName() ?>" height="140" width="140">
			<p><?= $noteImage->getImageName() ?></p>
	<?php
		}
	?>
			</div><!--box-->
		</div><!--left-->

		<div id="right">
			<h2>アカウント情報</h2>
			<table id="table">
                <tr>
                    <th><a data-target="con1" class="modal-open"><img src="data/<?= $account->getImage() ?>" width="200" height="200"></a></th>
                    <td>
                        <?= $account->getUserName() ?>
                        <p>@0000-0000-0000-<?= $account->getNo() ?></p>
                    </td>
                </tr>
            </table>
		</div>
	</div>

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
