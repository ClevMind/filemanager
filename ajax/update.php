<?php
session_start();

if(strip_tags($_POST['ENTER']) != 'TRUE') die();

require('../config.php');
require('../class/file_class.php');

$type 		= strip_tags($_POST['TYPE']);
$fold_name 	= trim(strip_tags($_POST['NAME']));
$cont 		= trim(strip_tags($_POST['CONT']));

$File = new cFile;
$test = '';

switch($type) {
	case 'UPDATE': 
			$_SESSION['PATH'] 		= $_SESSION['PATH'] . '/' . iconv('UTF-8', 'windows-1251', $fold_name);
			$path 					= '../' . $_SESSION['PATH'];
			
			echo  $File ->getTable($path, $config['fields']);
	break;
	
	case 'BACK':
		if($_SESSION['PATH'] == $config['path']) {
			echo $File ->getTable('../' . $config['path'], $config['fields']);
		} else {
			$pos 					= strripos($_SESSION['PATH'], '/');
			$path 					= substr($_SESSION['PATH'], 0, $pos);
			$_SESSION['PATH'] 		= $path;
			echo $File ->getTable('../' . $path, $config['fields']);
		}
	break;

	case 'CONTENT':
		echo $File ->getContent('../' . $_SESSION['PATH'], $fold_name);
	break;

	case 'SAFE':
		echo $File ->SafeFile('../' . $_SESSION['PATH'], $fold_name, $cont);
	break;

	case 'GETPATH':
		echo $File ->getPath();
	break;
}



?>