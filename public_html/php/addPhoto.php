<?php
error_reporting(E_ALL);
session_start();
if(($_SESSION["permission"] == null )|| ($_SESSION["permission"] < 15)){
	header("Location: http://bobrchess.of.by/news.php");	
}else{
	if(isset($_FILES['fupload']['name'])){$name = $_FILES['fupload']['name'];}
	
	//заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
	if (empty($name)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
	{
	  	header("Location: http://bobrchess.of.by/php/addPhoto.php");
	}      
	$date=time();
	$name = md5($name).$date;
	$source =    $_FILES['fupload']['tmp_name']; 
	$path = "../Image/Gallery/2015/".$name;
	move_uploaded_file($source,    $path);
	include ("bd.php");
	
	$result= $pdo->prepare("INSERT INTO gallery (path)    VALUES (:path)");
	$result->execute(array(":path"=>$path));
	header("Location: http://bobrchess.of.by/gallery.php");
}