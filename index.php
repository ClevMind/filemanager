<?php session_start(); ?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Удобный файловый менеджер</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div id="header">Удобный файловый менеджер</div>

<div id="path"> ТЕСТ</div>
<div id="main">

<?php 
require('config.php');
require('class/file_class.php');

$File = new cFile;

/* УБРАТЬ КОММЕНТАРИИ НА СЛУЧАЙ ИЗМЕНЕНИЯ $config['path']  */

//$File ->cleanPath();

if(isset($_SESSION['PATH'])) 
	echo $File ->getTable($_SESSION['PATH'], $config['fields']);
else {
	$_SESSION['PATH'] = $config['path'];
	echo $File ->getTable($config['path'], $config['fields']);
}

?>

</div>

<div style="display: none;">
    <div id="file_edit" style="width:800px; height:550px; overflow:auto;">
    <div id="file_name"></div>
    <textarea id="file_content" rows="30" cols="95"></textarea>
    <input id="safe" type="submit" value="Сохранить"/><div id="load"></div>
    </div>
</div>
	

	<script src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="js/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />
	<script src="js/main.js"></script>
</body>
</html>