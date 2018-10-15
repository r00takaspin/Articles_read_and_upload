<?php
header('Content-Type: text/html; charset=utf-8'); // ; charset=windows-1251
error_reporting(E_ALL);
define('ROOT_DIR',dirname(__FILE__));
define('ARTICLE_DIR', './articles/');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Статьи</title>
</head>
<body>
<!-- 1. Открывает файл - работает !!
	 2. Выводить первую строку из текстового файла как название строки
	 3. Не выводить ее в поле с текстом
-->
<?php
	if (isset($_GET['show'])) {
	?>
		<h1>Название статьи из первой строки</h1>
		
		<ul>
			<li><a href="../">Main</a>></li>
			<li>
				Название статьи из первой строки
			</li>
		</ul>
		
		<?php
			$text = file_get_contents(ROOT_DIR.ARTICLE_DIR.$_GET['show']);
		?>
		<div style="background: red;"><?=$text?></div>
	<?php
	}
	else {
?>
<!-- 1. Показывает список статей и их количество - работает
	 2. Сучьи ебучьи ромбики
	 3. Выводить только по 10 штук
	-->
	<h1>Статьи</h1>
	
		<ul>
			<?php 
			if ($handle = opendir(ROOT_DIR.ARTICLE_DIR)) {
				while (false !==($entry = readdir($handle))) {
					if ($entry !='.' and $entry != '..') {
						echo '<li><a href="index.php?show='.$entry.'">'.$entry.'</a></li>'.'<br>';
					}
				}
			}

			$dir = opendir(ARTICLE_DIR);
			$count = 0;
			while($file = readdir($dir)){
				if($file == '.' || $file == '..' || is_dir(ARTICLE_DIR. $file)){ // точки и папки не считаем
				continue;}
				$count++;}
			echo 'Всего статей: ' . $count;}
			?>

		</ul>
<!-- !!!!!!!! По ссылке открывается форма загрузки - работает, но должна открываться на новой странице -->
<?php
	if (isset($_GET['upload_form'])) {
	?>

	<h1>Загрузка</h1>
		<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="MAX_FILE_SIZE" value="30000">
			<input type="text" name="userfilename" placeholder="Название статьи"><br>
		    <input type="file" name="filename" class="select_file"><br>
		    <button name="upload_process" class="login_button">Загрузить</button>
		</form>
		<a href="index.php"> << Главная</a>

<!-- Имя статьи - 1 строка файла -->
<!-- Задавать имя статьи - добавлять 1 строчку к загружаемому файлу -->
<?php
    if(isset($_POST['userfilename'])){
}
?>
<!-- Загрузка файла - работает -->
<?php
    if(isset($_POST['upload_process'])){
		
        $allowed_filetype = array('.txt'); // допустимые форматы. 

        $err = array();#Массив с ошибками
        #Проверки

        $filename = $_FILES['filename']['name']; // получить имя файла с расширением
        $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // получить расширение файла

        if(!is_uploaded_file($_FILES["filename"]["tmp_name"])){
            $err[] = "Ошибка загрузки файла";
        }
        if($_FILES["filename"]['error']!= 0){
            $err[] = "Ошибка загрузки файла";
        };

        if($_FILES["filename"]['size'] > 1024*1024*5){
            $err[] = "Файл больше допусимого размера - 5 Мбайт";
        };

	if(count($err) == 0){
	    //Если файл загружен успешно, то перемещаем в конечную директорию
	    move_uploaded_file($_FILES["filename"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/articles/".$_FILES["filename"]["name"]);
	    echo "Загрузка прошла успешно!";
		} else{
		    #Вывод ошибок проверок
		    foreach($err AS $error){
		        print $error."<br>";
		    }
		}
}
?><?php
	}
?>
<a href="index.php?upload_form=">Загрузка статьи</a>
<!-- !!!!!!!! -->
</body>
</html>