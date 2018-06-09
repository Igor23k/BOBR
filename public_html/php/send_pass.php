<?php
 	if    (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);}    } //заносим введенный пользователем логин в переменную $login, если он пустой,    то уничтожаем переменную
	if    (isset($_POST['email'])) { $email = $_POST['email']; if ($email == '') {    unset($email);} } //заносим введенный пользователем e-mail, если он    пустой, то уничтожаем переменную	var_dump($email);
	if   (  (isset($login)) and  (isset($email) )  ) 
    {//если существуют необходимые переменные   
      	include ("bd.php");
     
      $result=$pdo->prepare("SELECT id FROM users WHERE login=:login AND    email=:email AND activation='1'");
$result->execute(array(":login"=>$login,":email"=>$email));
        $myrow = $result->fetch(PDO::FETCH_OBJ);
        if    ( empty($myrow->id) or ($myrow->id=='') ) 
        {     //если активированного пользователя с таким логином и е-mail    адресом нет
          exit ("Пользователя с    таким e-mail адресом не обнаружено ни в одной базе ЦРУ :) <a    href='index.php'>Главная страница</a>");
        }
    
        //если пользователь с таким логином и е-мейлом найден,    то необходимо сгенерировать для него случайный пароль, обновить его в базе и    отправить на е-мейл
        $datenow = date('YmdHis');//извлекаем    дату 
        $new_password = md5($datenow);// шифруем    дату
        $new_password = substr($new_password,    2, 6); //извлекаем из шифра 6 символов начиная    со второго. Это и будет наш случайный пароль. Далее запишем его в базу, зашифровав точно так же, как и обычно.
        $new_password_sh =    strrev(md5($new_password))."b3p6f";//зашифровали 
      
      $result=$pdo->prepare("UPDATE users SET    newPassword='$new_password_sh' WHERE login=:login");
        $result->execute(array(":login"=>$login)); // обновили в базе 
        //формируем сообщение
        $message = "Здравствуйте,    ".$login."!\nМы сгененриоровали для Вас пароль, теперь Вы сможете войти на сайт, используя его.\nПароль:".$new_password."\n\nС уважением, администрация сайта bobrchess.of.by.";//текст сообщения
        mail($email,    "Восстановление пароля", $message, "Content-type:text/plane");//отправляем сообщение          
      //        echo    "<html><head><meta http-equiv='Refresh' content='5;    URL=http://igor.saoworld.ru/'></head><body>На Ваш e-mail отправлено письмо с паролем. Вы    будете перемещены через 5 сек. Если не хотите ждать, то <a    					href='http://igor.saoworld.ru/'>нажмите сюда.</a></body></html>";//перенаправляем    пользователя
      	header("Location: http://bobrchess.of.by/registration.html");
    }
 	else    
    {
    	//если    данные еще не введены
    	echo "Вы не ввели email!и login!";
    }