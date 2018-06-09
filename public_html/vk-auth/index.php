<?php
class Main {
	public function constr($dataFromDB){	
      
		$arrayData = array();		
		foreach ($dataFromDB as $ValuesFromDataArr)
		{	
			foreach ($ValuesFromDataArr as $key=>$value)
			{	
              //$additionalArrForKeys[$key]=htmlspecialchars($value);
              $additionalArrForKeys[$key]=($value);
			}
			array_push($arrayData,$additionalArrForKeys);
		}
      	$arrayData=array_reverse($arrayData);
		return $arrayData;	
	}
	public function Connect(){ 
		$con = mysqli_connect("localhost","cb67060_igor","Igor451005","cb67060_igor"); 	
		if(!$con)
			exit("Ошибка подключения к БД!"); 
		$con->set_charset('utf8'); 
		return $con; 
	}
	function loginationUser($con,$vkID,$login,$vkLink,$sex,$birthday) {
    	$result = mysqli_query($con,"SELECT * FROM user WHERE vkID = '$vkID'");  
		$userData = array(); 
		while($row = mysqli_fetch_assoc($result)){ 
			$userData[] = $row; 
		}; 
		if (count($userData) == 0){
			mysqli_query($con,"INSERT INTO user (`vkID`, `login`, `vkLink`, `sex`, `birthday`) VALUES ('$vkID', '$login','$vkLink', '$sex','$birthday')");
			session_start();
			$_SESSION['login'] = $$login;
			//var_dump("kek");
		}else{
			$birthday="2019-01-01";
			mysqli_query($con,"UPDATE users SET login = '$login',vkLink = '$vkLink',sex = '$sex',birthday = '$birthday'  WHERE vkID = '$vkID'");
			session_start();
			$_SESSION['login'] = $userData[0]['login'];
			$_SESSION['idUser'] = $userData[0]['id'];
			$_SESSION['permission'] = $userData[0]['permission'];
		}

    }
}
 

$client_id = '6439989'; // ID приложения
$client_secret = 'yG4Hu8h6M1TxfxZQc6Vw'; // Защищённый ключ
$redirect_uri = 'http://bobrchess.of.by/vk-auth'; // Адрес сайта

$url = 'http://oauth.vk.com/authorize';

$params = array(
    'client_id'     => $client_id,
    'redirect_uri'  => $redirect_uri,
    'response_type' => 'code'
);

if (isset($_GET['code'])) {
$result = false;
$params = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'code' => $_GET['code'],
    'redirect_uri' => $redirect_uri
);

$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

if (isset($token['access_token'])) {
    $params = array(
        'uids'         => $token['user_id'],
        'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
        'access_token' => $token['access_token'],
        'screen_name' => $token['screen_name'],
        'sex' => $token['sex']
    );
	//тут чет не так стало, вдруг
    $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . "&v=5.71".urldecode(http_build_query($params))), true);

$userInfo = $userInfo['response'][0];
    if (isset($userInfo['id'])) {
    	 $result = true;
    }
}


if ($result) {
    //echo "Социальный ID пользователя: " . $userInfo['uid'] . '<br />';
    $vkID = $userInfo['id'];
    //echo "Имя пользователя: " . $userInfo['first_name'] . '<br />';
    $login = $userInfo['first_name'];
   // echo "Ссылка на профиль пользователя: " . $userInfo['screen_name'] . '<br />';
    $vkLink = $userInfo['screen_name'];
   // echo "Пол пользователя: " . $userInfo['sex'] . '<br />';
    $sex = $userInfo['sex'];
   // echo "День Рождения: " . $userInfo['bdate'] . '<br />';
    $birthday = $userInfo['bdate'];
   // echo '<img src="' . $userInfo['photo_big'] . '" />'; echo "<br />";
    //$avatar = $userInfo['photo_big'];
    
    //echo $login;
    $Obj = new Main();
	$Obj->loginationUser($Obj->Connect(),$vkID,$login,$vkLink,$sex,$birthday);
}
}
header("Location: http://bobrchess.of.by/");