<?php
error_reporting(E_ALL);
session_start();
if(($_SESSION["permission"] == null )|| ($_SESSION["permission"] < 15)){
	header("Location: http://bobrchess.of.by/news.php");	
}else{
	$idNews=$_POST['myID'];
	$title=$_POST['title'];
	$subtitle=$_POST['subtitle'];
	$theme=$_POST['theme'];
	$msg=$_POST['msg'];
	 
	$checkBox=$_POST['checkBox'];
	$picture="";
	if($checkBox==null){
		$picture =  $_FILES['fupload']['tmp_name'];
		if($picture==""){
			$picture="default";
		}
	}else{
		$picture="default";
	}
	
	$pathToDirectory = '../Image/AllNews/';        
	$pathToOtherDirectoryWithDefinedImage = '../images/'; 
	$date=time();
	$path="";
	if ($picture=="default"){
		$picture="features-bg.jpg";
		$path = $pathToOtherDirectoryWithDefinedImage.$picture;
	}else if ($picture != null){
			$picture = md5($name).$date;
			$path = $pathToDirectory.$picture;
			move_uploaded_file($_FILES['fupload']['tmp_name'],    $path);
		}
	
	
	include ("bd.php");
	
	//Найти все вхождения <img  и вставить img-responsive... //сделать проверку, что,если уже есть этот класс - не добавлять
	$strToInsert   = ' class="img-responsive" ';
	$findme   = '<img';
	$pos=0;
	do {
		$pos = strpos($msg, $findme,$pos+2);
	 	if($pos){
		    $msg = substr_replace($msg, $strToInsert, $pos+4, 0);
	 	}
	} while ($pos);
	
	if ($idNews!=0){
	  	$result= $pdo->prepare("UPDATE news SET title=:title,subtitle=:subtitle,text=:text,type=:type WHERE idNews=:idNews");
	  	$result->execute(array(":title"=>$title,":subtitle"=>$subtitle,":text"=>$msg,":type"=>getNumberOfTheme($theme),":idNews"=>$idNews));
	}else{
		$result= $pdo->prepare("INSERT INTO news (title,subtitle,path,text,date,author,type)    VALUES (:title,:subtitle,:path,:text,now(),:author,:type)");
		$result->execute(array(":title"=>$title,":subtitle"=>$subtitle,":path"=>$path,":text"=>$msg,":type"=>getNumberOfTheme($theme),":author"=>$_SESSION["idUser"]));//type исправить
	}
}

function getNumberOfTheme($theme) {
  switch ($theme) {
    case "tournament":
        return 1;
    case "site":
        return 2;
    case "club":
        return 3;
    case "world":
        return 4;
    case "author":
        return 5;
    case "other":
        return 6;
	}
}

header("Location: http://bobrchess.of.by/news.php");