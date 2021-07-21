<?php
session_start();

$validationMsg=null;
if(isset($_SESSION["validationMsg"])){
	$validationMsg=$_SESSION["validationMsg"];
}

$validationMsgs=null;
if(isset($_SESSION["validationMsgs"])){
	$validationMsgs=$_SESSION["validationMsgs"];
}

if(isset($_SESSION["insertAccount"])){
	$insertAccount=$_SESSION["insertAccount"];
}

$userName="";
if(isset($_SESSION["userName"])){
	$userName=$_SESSION["userName"];
}

$password="";
if(isset($_SESSION["password"])){
	$password=$_SESSION["password"];
}

session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Study&nbsp;Note</title>
<!--BootStrap_CSS-->
<link rel="stylesheet" href="css/bootstrap_css/bootstrap.css">
<link rel="stylesheet" href="css/bootstrap_css/bootstrap.min.css">
<!--BootStrap_CSS-->
<link rel="stylesheet" type="text/css" href="css/index_sub.css">
</head>
<body>
<div id="wrapper">
	<header>
		<div id="navi">
			<ul>
				<li><a data-target="con2" class="modal-open">ログイン</a></li>
				<li><a data-target="con1" class="modal-open">アカウント登録</a></li>
			</ul>
		</div>

	</header>
		<div id="contents">
			<h1>Study&nbsp;Note</h1>
			<p><img src="images/6706.png" alt="" width="300" height="350"></p>
		<!--バリデーションメッセージなど-->
		<?php
		if(!is_null($validationMsgs)){
		?>
				<div id="message">
					<ul>
		<?php
				foreach ($validationMsgs as $msgs ) {
		?>
						<li><?= $msgs ?></li>
		<?php
				}
		?>
					</ul>
				</div>
		<?php
		}
		if(!is_null($validationMsg)){
		?>
				<div id="message">
					<pre>＜アカウント登録エラー＞</pre>
					<ul>
		<?php
				foreach ($validationMsg as $msg ) {
		?>
						<li><?= $msg ?></li>
		<?php
				}
		?>
					</ul>
				</div>
		<?php
		}
		if(isset($insertAccount)){
		?>
				<div id="messages">
					<ul>
						<li>アカウント登録しました</li>
					</ul>
				</div>		
		<?php		
		}
		?>
		</div>
</div>

<div id="sub_contents">
	<h2 id="studyNote">「Study&nbsp;Note」</h2>
		<p>ノートを取るように情報を蓄積するソフトウェアないしウェブサービスである。</p>

		<p>また、ノートの取り方はメモを取るときやドキュメントを書くときにとても便利な<em>「※マークダウン記法」</em>を採用。</p>
	<div id="a">
		<img src="images/logo.png" alt="マークダウン" width="350" height="350" id="logo">
		<div id="whatMarkdown">
			<p>※メールを記述する時のように書きやすくて読みやすいプレーンテキストをある程度見栄えのするHTML文書へ変換できるフォーマットとしてジョン・グルーバーによって開発されました。</p>
		</div>
	</div>
<hr>
<div id="toUse">
	<h2>「Function」</h2>
	<div id="toUseMarkdown">
		<div id="toUseLeftMarkdown">
			<h3>＜マークダウン＞</h3>
			<p>綺麗に且つ、素早く取れるようにマークダウンを採用。</p>
		</div>
		<div id="toUseRightMarkdown">
			<img src="images/markdown.png" width="500" height="300">
		</div>
	</div>

	<div id="toUseMypage">
		<div id="toUseLeftMypage">
			<h3>＜マイページ＞</h3>
			<p>個人レベルでページを生成でき、タグ付けなどをしてノートを管理ができ、共有機能で友達と繋がることができる。</p>
		</div>
		<div id="toUseRightMypage">
			<img src="images/mypage.png" width="500" height="300">
		</div>
	</div>
</div>
<hr>

<div id="join">
	<h2>「UsingSoon」</h2>
		<p><a data-target="con1" class="modal-open">アカウント登録</a></p>
</div>
</div>

<!--ログインボタンのモーダルの中身-->
<div id="con2" class="modal-content">
	<form action="php/selectLogin.php" method="post">
		<h2>ログイン</h2>
			<input type="text" name="user" placeholder="アカウント名" value="Guest" class="hoge">
			<input type="password" name="pass" placeholder="ログインパスワード" value="passW0rd" class="hoge">
			<input type="submit" value="ログイン">
		</form>
	</div>

<!--アカウント登録ボタンのモーダルの中身-->
<div id="con1" class="modal-content">
	<form action="php/insertAccount.php" method="post" enctype="multipart/form-data">
		<h2>アカウント登録</h2>
			<input type="text" name="userName" placeholder="アカウント名" class="hoge">

			<div id="drag-drop-area">
			    <div class="drag-drop-inside">
			      <p class="drag-drop-info">ここにファイルをドロップ</p>
			      <p>または</p>
			      <p class="drag-drop-buttons"><input id="fileInput" type="file" value="ファイルを選択" name="image"></p>
			    </div>
			</div>

			<input type="password" name="pass" placeholder="ログインパスワード" class="hoge">
			<input type="password" name="share_pass" placeholder="共有パスワード" class="hoge">

			<input type="submit" value="アカウント登録">
	</form>
</div>


</body>
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/modal.js"></script>
<script src="js/photoAdd.js"></script>
<script type="text/javascript">
$(function(){
	$("#messages").css("display","none");
	$("#messages")
		.fadeIn().delay(3000).fadeOut(1000);
		
});
</script>
</html>