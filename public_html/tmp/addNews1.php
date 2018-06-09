<?php
error_reporting(E_ALL);
session_start();
if (isset($_POST['title'])) { $title = $_POST['title']; if ($title == '') { unset($title);} } 
if (isset($_POST['subtitle'])) { $subtitle = $_POST['subtitle']; if ($subtitle == '') { unset($subtitle);} } 
if (isset($_POST['msg'])) { $msg = $_POST['msg']; if ($msg == '') { unset($msg);} }
if (isset($_POST['theme'])) { $theme = $_POST['theme']; if ($theme == '') { unset($theme);} }
if(isset($_FILES['fupload']['name'])){$name = $_FILES['fupload']['name'];}

if (empty($title) or empty($msg) or empty($subtitle) or empty($theme) )
{
  header("Location: http://bobrchess.of.by/html/addNews.html");
}
$pathToDirectory = '../Image/AllNews/';        
$pathToOtherDirectoryWithDefinedImage = '../images/'; 
$date=time();
if ($name==""){
	$name="features-bg.jpg";
	$path = $pathToOtherDirectoryWithDefinedImage.$name;
}else{
	$name = md5($name).$date;
	$path = $pathToDirectory.$name;
}
$source =    $_FILES['fupload']['tmp_name']; 

move_uploaded_file($source,    $path);
include ("bd.php");
$result= $pdo->prepare("INSERT INTO news (title,subtitle,path,text,date,author,type)    VALUES (:$title,:subtitle,:path,:text,now(),:author,:type)");
$result->execute(array(":$title"=>$title,":subtitle"=>$subtitle,":path"=>$path,":text"=>$msg,":type"=>"2",":author"=>$_SESSION["idUser"]));//type исправить
header("Location: http://bobrchess.of.by/news.php");