<?php
  $pdo = new PDO('mysql:host=localhost;dbname=cb67060_igor', "cb67060_igor", "Igor451005");

  $result = $pdo->prepare("SELECT pathToAvatar FROM    user WHERE activation='0'    AND    UNIX_TIMESTAMP()    - UNIX_TIMESTAMP(date)    > 3600");
  $result->execute();
	
// if    ($result->rowCount() > 0) 
// {
    //   $myrow = $result->fetch(PDO::FETCH_OBJ);
    //var_dump($myrow->avatar);
//    do 
//  {
      
      //удаляем    аватары в цикле, если они не стандартные
//    if    ($myrow->avatar == "avatars/net-avatara.jpg") 
//   {
//      $a = "Ничего    не делать";
//   }
//   else   
//  {
  //     unlink ($myrow['avatar']);//удаляем    файл
  //   }
  //	}
  // while($myrow = $result->fetch(PDO::FETCH_OBJ));
  //  }
	
  $result = $pdo->prepare("DELETE FROM user WHERE activation='0' AND UNIX_TIMESTAMP() -    UNIX_TIMESTAMP(date) > 3600");
  $result->execute();

  if    (isset($_GET['code'])) 
  {
    $code =$_GET['code']; 
  } //код подтверждения 
  else 
  {    
    exit("Вы    зашли на страницу без кода подтверждения!");
  } //если не указали code,    то выдаем ошибку
	
  $result = $pdo->prepare("SELECT    *    FROM    user WHERE hash='$code' LIMIT 1");//извлекаем    идентификатор пользователя с данным логином
  $result->execute();

  $myrow = $result->fetch(PDO::FETCH_OBJ);
  
  if ($myrow !=null) 
  {//сравниваем полученный из url и сгенерированный код 
    
    $result = $pdo->prepare("UPDATE    user SET activation='1' WHERE hash='$code'");//если равны, то активируем пользователя!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$result->execute();
    header("Location: http://bobrchess.of.by");
    //  echo "Ваш Е-мейл подтвержден! Теперь вы можете    зайти на сайт под своим логином! <a href='index.php'>Главная    страница</a>";
  }
  else 
  {
    echo "Ошибка! Ваш Е-мейл не    подтвержден! <a    href='http://bobrchess.of.by/index.php'>Главная    страница</a>";//если    же полученный из url и    сгенерированный код не равны, то выдаем ошибку          
  }