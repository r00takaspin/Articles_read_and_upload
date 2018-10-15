<?php
header('Content-Type: text/html; charset=utf-8'); // ; charset=windows-1251
error_reporting(E_ALL);
define('ROOT_DIR',dirname(__FILE__));
define('ARTICLE_DIR', '/articles/');
define('PER_PAGE', 10);
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
	// перенеси это в отдельный файл show.php
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
			$page = intval(@$_GET['page']) || 0;
			$entries = array();
			$titles = array();

			if ($handle = opendir(ROOT_DIR . ARTICLE_DIR)) {
				// получаем названия статей и названия файлов 

				while (false !==($entry = readdir($handle))) {
					if ($entry !='.' and $entry != '..') {
						$entries[] = $entry;

						// читаем заголовок из файла
						$file = fopen(ROOT_DIR . ARTICLE_DIR . $entry, 'r');
						$title = fgets($file);
						fclose($file);
						$titles[] = $title;
					}
				}
			}

			$entries = array_slice($entries, PER_PAGE * $page);

			foreach($entries as $t => $e) { 
				echo '<li><a href="index.php?show='.$entry.'">'.$titles[$t].'</a></li>'.'<br>';
			}


			// постраничка
			for($i=0; $i<count($entries) / PER_PAGE; $i++) { 
				?>
					<a href="index.php?page=<?=$i?>"><?=$i+1?></a>
				<?
			}

			?><BR><?

			echo 'Всего статей: ' . count($entries);
}
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
