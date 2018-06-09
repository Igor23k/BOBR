<?php
	error_reporting(E_ALL);
	session_start();
	$template;
	$args;
	define('U_READ', 1 << 0);   // 0001
    define('U_CREATE', 1 << 1); // 0010
    define('U_EDIT', 1 << 2);   // 0100
    define('U_DELETE', 1 << 3); // 1000
    define('U_ALL', U_READ | U_CREATE | U_EDIT | U_DELETE); // 1111
	
        
	function constr($publications){	
		$arrayData = array();		
		foreach ($publications as $ValuesFromDataArr)
		{	
			foreach ($ValuesFromDataArr as $key=>$value)
			{	
				$additionalArrForKeys[$key]=htmlspecialchars($value);
			}
			array_push($arrayData,$additionalArrForKeys);			
		}
		return $arrayData;	
	}
	function Connect(){ 
		$con = mysqli_connect("localhost","cb67060_igor","Igor451005","cb67060_igor"); 	
		if(!$con)
			exit("Ошибка подключения к БД!"); 
		$con->set_charset('utf8'); 
		return $con; 
	}
	
	function replaceArrayOfContent($tplFile,$arrayOfData){
			$templateStr = file_get_contents($tplFile);
			$finalStr = "";
			foreach ($arrayOfData as $array)
			{
				$tempStr=$templateStr;
				foreach ($array as $key=>$values)
				{		
					$tempStr = str_replace('{'.$key.'}',$values,$tempStr);
				}
				$finalStr.=$tempStr;
			}
			return $finalStr;	
	}
