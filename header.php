<?php
	// header('Expires: Tue, 1 Jan 2020 00:00:00 GMT');
	// header('Last-Modified:' . gmdate( 'D, d M Y H:i:s' ) . 'GMT');
	// header('Cache-Control:no-cache,no-store,must-revalidate,max-age=0');
	// header('Cache-Control:pre-check=0,post-check=0',false);
	// header('Pragma:no-cache');
	?>
	<!DOCTYPE html>
	<html lang="ja">
	<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="other/css/style.css">
	<link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">
	<!-- font Awesomeを使用 -->
	<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
	<!-- Chart.jsを使用する -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js" type="text/javascript"></script>
	<!-- jQueryを使用 -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	
	
	
	<title>家計簿</title>
	</head>
	<body class="
	<?php
	$url=$_SERVER['REQUEST_URI'];
	if($url=="/kakeibo/index.php"):
	echo "bg-index";
	elseif($url=="/kakeibo/login.php"):
	echo "bg-input";
	else:
	echo "bg-else";
	endif;
	
	?>">
	
	<header class="header">
	<div class="container">
	<h1 class="header__title">家計簿アプリ</h1>
	<?php
	$url=$_SERVER['REQUEST_URI'];
	if($url=="/kakeibo/index.php"):
	?>
	<div class="header__button">
	<a class="button button--logout" href="logout.php">ログアウト</a>
	</div>
	<?php endif;?>
	
	</div>
	
	
	</header>