<?php
error_reporting(E_ALL);
session_start();
if(($_SESSION["permission"] == null )|| ($_SESSION["permission"] < 15)){
	header("Location: http://bobrchess.of.by/news.php");	
}else{
	$id=$_GET["newsID"];
	include ("bd.php");
	$result = $pdo->prepare("DELETE FROM news WHERE idNews = $id");
	$result->execute();
	header("Location: http://bobrchess.of.by/news.php");
}