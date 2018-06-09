<?php
session_start();//  вся процедура работает на сессиях. Именно в ней хранятся данные  пользователя, пока он находится на сайте. Очень важно запустить их в  самом начале странички!!!
if (isset($_POST['email'])) { $email = $_POST['email']; if ($email == '') { unset($email);} } //заносим введенный пользователем логин в переменную $email, если он пустой, то уничтожаем переменную
if (isset($_POST['pass'])) { $password=$_POST['pass']; if ($password =='') { unset($password);} }

if (empty($email) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
{
    exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
}

//если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести

//$email = stripslashes($email);
//$email = htmlspecialchars($email);
//$password = stripslashes($password);
//$password = htmlspecialchars($password);
//удаляем лишние пробелы
$email = trim($email);
$password = trim($password);
$password    = md5($password);//шифруем пароль
$password    = strrev($password);// для надежности добавим реверс
$password    = $password."b3p6f";
$ip=$_SERVER['REMOTE_ADDR'];

 
include ("bd.php");
$result2=$pdo->prepare("DELETE FROM loginfailed WHERE UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 60");

$result2->execute();
$dbh = $pdo->prepare("SELECT counterrors FROM loginfailed WHERE   ip='$ip'");
if ($dbh->execute()) {
	$myrow = $dbh->fetch(PDO::FETCH_OBJ);
	if ($myrow!=NULL){
		if ($myrow->counterrors > 2) {
				//если ошибок больше двух, т.е три, то выдаем сообщение.
				exit("Вы набрали логин или пароль неверно 3 раза. Подождите 1 минуту до следующей попытки.");
				}   
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////
$dbh = $pdo->prepare("SELECT * FROM user WHERE email='$email' AND    activation='1' LIMIT 1");

$dbh->bindParam(':email', $email, PDO::PARAM_STR);

if ($dbh->execute()) {
  		
        $myrow = $dbh->fetch(PDO::FETCH_OBJ);
        if ( ($myrow!=NULL) && ($myrow->newPassword == $password) )
        {
          $_SESSION['email'] = $myrow->email;
          $_SESSION['login'] = $myrow->login;
          $_SESSION['password'] = $myrow->newPassword;
          $_SESSION['permission'] = $myrow->permission;
          $login=$_SESSION['login'];
          $pass=$_SESSION['password'];
          $dbh = $pdo->prepare("UPDATE user SET password=:password WHERE login=:login");
		  $dbh->execute(array(":password"=>$pass,":login"=>$login));
          $result2=$pdo->prepare("UPDATE user SET newPassword=''WHERE login=:login");
		  $result2->execute(array(":login"=>$login));
          header("Location: http://bobrchess.of.by/");         
        } 
  		else if (($myrow!=NULL) && ($myrow->password == $password))
        {
          $_SESSION['email'] = $myrow->email;
          $_SESSION['login'] = $myrow->login;
          $_SESSION['password'] = $myrow->password;
          $_SESSION['permission'] = $myrow->permission;
          $_SESSION['idUser'] = $myrow->id;
          $login=$_SESSION['login'];
          $result2=$pdo->prepare("UPDATE user SET newPassword=''WHERE login=:login");
		  $result2->execute(array(":login"=>$login));
          //эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь
          //  echo "Вы успешно вошли на сайт! <a href='http://igor.saoworld.ru/'>Главная страница</a>";
          header("Location: http://bobrchess.of.by");         
        }
        else 
        {
          //если пароли не сошлись	
          $dbh = $pdo->prepare("SELECT * FROM loginfailed WHERE ip='$ip' LIMIT 1");
          $dbh->bindParam(':ip', $ip, PDO::PARAM_STR);
          if ($dbh->execute()) 
          {
            $myrow = $dbh->fetch(PDO::FETCH_OBJ);
            if ($myrow!=NULL)
            {        
              if ($myrow->ip == $ip) 
              {//проверяем, есть ли пользователь в таблице "oshibka"          
                $counterrors = $myrow->counterrors + 1;//прибавляем    еще одну попытку неудачного входа 
                $result2=$pdo->prepare("UPDATE loginfailed SET counterrors=$counterrors,date=NOW() WHERE    ip='$ip'");
                $result2->execute();		
              }  
      	  	}
            else 
            {
              $result2=$pdo->prepare("INSERT INTO loginfailed (ip,date,counterrors) VALUES    ('$ip',NOW(),'1')");
              $result2->execute();
            }   
         }
         exit ("Извините, введённый вами email или пароль неверный!");
    }	
}
header("Location: http://bobrchess.of.by");       