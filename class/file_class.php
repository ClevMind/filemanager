<?php

class cFile
{

	private $folder_info 	= array();
	private $index 			= 0;


	public function folderExist($folder)
	{
		return file_exists($folder) ? true : false;
	}

	public function getInfo($path) 
	{
		/*
		*	ВОЗВРАЩАЕТ ИНФОРМАЦИЮ О ДИРЕКТОРИИ
		*/
		if($this ->folderExist($path) === TRUE) {
			$dir 			= scandir($path);
			$this ->index 	= 0;
			$info  			= array();
			foreach ($dir as $value) {
				if(!preg_match('/^[.]+$/',$value)) {
					$this ->folder_info['name' . $this ->index] 		=  iconv('windows-1251', 'UTF-8', $value);
					if(is_dir($path . '/' . $value))
						$this ->folder_info['is_fold' . $this ->index] = true;
					else
						$this ->folder_info['is_fold' . $this ->index] = false;

					$info =  stat($path . '/' . $value);

					$this ->folder_info['size' . 		$this ->index] = $info['size'] / 1000 . ' мб.';
					$this ->folder_info['create' . 		$this ->index]	= date('d.m.Y', $info['mtime']);
					$this ->folder_info['modified' . 	$this ->index]	= date('d.m.Y', $info['ctime']);
					$this ->index++;
				}
			}

		} else {
			echo "Ошибка, директория '" . $path . "' не существует.";
			exit();
		}
	}


	public function getTable($path, $fields) 
	{
		/*
		*	ФОРМИРУЕТ ТАБЛИЦУ
		*/
		$this ->getInfo($path);

		$cont  		= "<table id='main_table'>";
		$cont 	   .= $this ->getFields($fields);
		$type  		= '';
		$name  		= '';
		$flag  		= false;

		for($i = 0; $i < $this ->index; $i++) {
			$type 	= ' ';
			$click  = ' ';
			$flag 	= false;
			if($this ->folder_info['is_fold' . $i]) {
				$flag  = true;
				$type  = 'type_folder';
			} else {
				$end = substr(strrchr($this ->folder_info['name' . $i], '.'), 1);
				if($end == 'txt') {
					$type = 'type_file_txt';
					$flag = true;
				} else 
					$type = 'type_file';
			}

			$name 	= $this ->folder_info['name' . $i];
			if($flag)
				$click 	= "onclick='change_me(/" . $name . "/)'";

			$cont .= "<tr><td class='first_td'><div href='#file_edit' " . $click . " class='" . $type . "'></div>" . $this ->folder_info['name' . $i] . "<div class='change'></div></td><td>" . $this ->folder_info['size' . $i] . "</td><td>" . $this ->folder_info['create' . $i] . "</td><td>" . $this ->folder_info['modified' . $i] . "</td></tr>";
		}
		return $cont . '</table>';
	}


	public function getFields($fields)
	{
		/*
		*	ФОРМИРУЕТ СТАРТОВЫЕ ПОЛЯ ДЛЯ ТАБЛИЦЫ
		*/
		$cont = '<tr>';
		for($i = 0; $i < sizeof($fields); $i++) {
			if(!$i)
				$cont .= "<td><div id='go_back' onclick='goBack()'></div><b>" . $fields[$i] . "</b></td>";
			else
				$cont .= "<td><b>" . $fields[$i] . "</b></td>";
		}
		return $cont . '</tr>';
	}


	public function getContent($dir, $file_name)
	{
		/*
		*	ПОЛУЧАЕТ СОДЕРЖИМОЕ ФАЙЛА
		*/
		$cont = '';
		$info = array();
		$url  = $dir . '/' . iconv('UTF-8', 'windows-1251', $file_name);
		if($this ->folderExist($url) === TRUE) {
			if(($cont = file_get_contents($url)) !== FALSE) {
				$info['cont'] 	= $cont;
				$info['url']	= $url; 	
				if(is_writable($url)) 
					$info['is_writable'] = true;
				 else 
					$info['is_writable'] = false;

				return json_encode($info);
			}
			else {
				echo "Ошибка чтения файла " . $url;
				exit();
			}
		} else {
			echo "Файл " . $url . " не существует!";
			exit();
		}
	}

	public function SafeFile($dir, $file_name, $content)
	{
		/*
		*	СОХРАНЯЕТ ФАЙЛ
		*/
		$url  = $dir . '/' . iconv('UTF-8', 'windows-1251', $file_name);
		if($this ->folderExist($url) === TRUE) {
			if(($file = fopen($url, "w")) != FALSE) {
				 if (fwrite($file, json_decode($content)) === FALSE) {
				 	echo "Ошибка в записи файла " . $url ;
				 	exit();
				 } else {
				 	fclose($file);	
				 	return true;		
				 }
			} else {
				echo "Ошибка в открытии файла " . $url ;
				exit();
			}
		} else {
			echo "Файл " . $url . " не существует!";
			exit();
		}
	}

	public function getPath()
	{
		return $_SESSION['PATH'];
	}

	public function cleanPath()
	{
		unset($_SESSION['PATH']);
	}

}

?>