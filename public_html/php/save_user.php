<?php 

define('U_READ', 1 << 0);   // 0001
define('U_CREATE', 1 << 1); // 0010
define('U_EDIT', 1 << 2);   // 0100
define('U_DELETE', 1 << 3); // 1000
define('U_ALL', U_READ | U_CREATE | U_EDIT | U_DELETE); // 1111
if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } 
if (isset($_POST['email'])) { $email = $_POST['email']; if ($email == '') { unset($email);} }
if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
if (isset($_POST['passwordConfirmation'])) { $passwordConfirmation=$_POST['passwordConfirmation']; if ($passwordConfirmation =='') { unset($passwordConfirmation);} }

//заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
if($password !=$passwordConfirmation){
	 exit ("Пароли не совпадают!");
}
if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
{
     exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
}
if    (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email)) //проверка    е-mail адреса регулярными выражениями на корректность
            {
  exit    ("Неверно введен е-mail!");
            }
$login = stripslashes($login);

$email = stripslashes($email);
$email = htmlspecialchars($email);

$password = stripslashes($password);
$password = htmlspecialchars($password);

$login = trim($login);
$email = trim($email);
$password = trim($password);

if    (!empty($_POST['fupload'])) //проверяем, отправил    ли пользователь изображение
            {
            	$fupload=$_POST['fupload'];    $fupload = trim($fupload); 
				if ($fupload =='' or empty($fupload)) 
                {
                	unset($fupload);                                          
            	} 
			}
if    (!isset($fupload) or empty($fupload) or $fupload =='')
            {
            	//если переменной не существует (пользователь не отправил    изображение),то присваиваем ему заранее приготовленную картинку с надписью    "нет аватара"
            	$avatar    = "avatars/net-avatara.jpg"; //можете    нарисовать net-avatara.jpg или взять в исходниках
            }          
else 
            {				
              //иначе - загружаем изображение пользователя
              $path_to_90_directory    = 'avatars/';//папка,    куда будет загружаться начальная картинка и ее сжатая копия                   
              if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['fupload']['name']))//проверка формата исходного изображения
                      {                 
                        $filename =    $_FILES['fupload']['name'];
                        $source =    $_FILES['fupload']['tmp_name']; 
                        $target =    $path_to_90_directory . $filename;
                        move_uploaded_file($source,    $target);//загрузка оригинала в папку $path_to_90_directory           
         				if(preg_match('/[.](GIF)|(gif)$/',    $filename)) 
                        {
                     		$im    = imagecreatefromgif($path_to_90_directory.$filename) ; //если оригинал был в формате gif, то создаем    изображение в этом же формате. Необходимо для последующего сжатия
                     	}
                     	if(preg_match('/[.](PNG)|(png)$/',    $filename)) 
                        {
                     		$im =    imagecreatefrompng($path_to_90_directory.$filename) ;//если    оригинал был в формате png, то создаем изображение в этом же формате.    Необходимо для последующего сжатия
                     	}
                     
                     	if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/',    $filename)) 
                        {
                            $im =    imagecreatefromjpeg($path_to_90_directory.$filename); //если оригинал был в формате jpg, то создаем изображение в этом же    формате. Необходимо для последующего сжатия
                     	}           
                        //СОЗДАНИЕ КВАДРАТНОГО ИЗОБРАЖЕНИЯ И ЕГО ПОСЛЕДУЮЩЕЕ СЖАТИЕ    ВЗЯТО С САЙТА www.codenet.ru           
                        // Создание квадрата 90x90
                        // dest - результирующее изображение 
                        // w - ширина изображения 
                        // ratio - коэффициент пропорциональности           
						$w    = 90;  //    квадратная 90x90. Можно поставить и другой размер.          
						// создаём исходное изображение на основе 
            			// исходного файла и определяем его размеры 
                        $w_src    = imagesx($im); //вычисляем ширину
                        $h_src    = imagesy($im); //вычисляем высоту изображения
                        // создаём    пустую квадратную картинку 
                        // важно именно    truecolor!, иначе будем иметь 8-битный результат 
                       	$dest = imagecreatetruecolor($w,$w);           
                        //    вырезаем квадратную серединку по x, если фото горизонтальное 
                       	if    ($w_src>$h_src) 
                       		imagecopyresampled($dest, $im, 0, 0,
                                         round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                                      	0, $w, $w,    min($w_src,$h_src), min($w_src,$h_src));           
           				// вырезаем    квадратную верхушку по y, 
                     	// если фото    вертикальное (хотя можно тоже серединку) 
                    	 if    ($w_src<$h_src) 
                   			  imagecopyresampled($dest, $im, 0, 0,    0, 0, $w, $w,
                                      min($w_src,$h_src),    min($w_src,$h_src));           
        				 // квадратная картинка    масштабируется без вырезок 
                     	if ($w_src==$h_src) 
                    		 imagecopyresampled($dest,    $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src);           
						$date=time();    //вычисляем время в настоящий момент.
           				imagejpeg($dest,    $path_to_90_directory.$date.".jpg");//сохраняем изображение формата jpg в нужную папку, именем будет текущее время.Сделано,чтобы у аватаров не было одинаковых имен.          
                        //почему именно jpg? Он занимает очень мало места + уничтожается    анимирование gif изображения, которое отвлекает пользователя. Не очень    приятно читать его комментарий, когда 							краем глаза замечаешь какое-то    движение.          
						$avatar    = $path_to_90_directory.$date.".jpg";//заносим в переменную путь до аватара. 
						$delfull    = $path_to_90_directory.$filename; 
            			unlink    ($delfull);//удаляем оригинал загруженного    изображения, он нам больше не нужен. Задачей было - получить миниатюру.
            		}
            		else 
                     {
                        //в случае    несоответствия формата, выдаем соответствующее сообщение
                      	exit ("Аватар должен быть в    формате <strong>JPG,GIF или PNG</strong>");
                     }
            //конец процесса загрузки и присвоения переменной $avatar адреса    загруженной авы
            }   

$password    = md5($password);//шифруем пароль          
$password    = strrev($password);// для надежности добавим реверс          
$password    = $password."b3p6f";

            //можно добавить несколько своих символов по вкусу, например,    вписав "b3p6f". Если этот пароль будут взламывать методом подбора у    себя на сервере этой же md5,то явно ничего хорошего не выйдет. Но советую    ставить другие символы, можно в начале строки или в середине.          
//При этом необходимо увеличить длину поля password в базе.    Зашифрованный пароль может получится гораздо большего размера.          
// дописали новое********************************************          
// Далее идет все из первой части статьи,но необходимо    дописать изменение в запрос к базе. 




include ("bd.php");
$result = $pdo->prepare("SELECT id FROM user WHERE login='$login'");
$result->execute();
$myrow = $result->fetch(PDO::FETCH_OBJ);

if ($myrow!=NULL){		
  	exit ("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");
}
$result = $pdo->prepare("SELECT id FROM user WHERE email='$email'");
$result->execute();
$myrow = $result->fetch(PDO::FETCH_OBJ);

if ($myrow!=NULL){		
  	exit ("Извините, введённый вами email уже занят. Если вы забыли пароль, то вы можете его восстановить.");
}
if($email=="www.bk.ru@mail.ru"){
	$user_perm =  U_ALL;
}
else{
	$user_perm =  U_READ; // только право чтения
}
$activation    = md5(htmlspecialchars($myrow->id)).md5(htmlspecialchars($login));
$result = $pdo->prepare("INSERT INTO user (login,password,hash,email,permission)    VALUES (:login,:password,:activation,:email,:permission)");
$result->execute(array(":login"=>$login,":password"=>$password,":email"=>$email,":permission"=>$user_perm,":activation"=>$activation));

if ($result==TRUE)
{
   $result = $pdo->prepare("SELECT id FROM user WHERE login='$login'");
   $result->execute();//извлекаем    идентификатор пользователя. Благодаря ему у нас и будет уникальный код    активации, ведь двух одинаковых идентификаторов быть не может.
   $myrow = $result->fetch(PDO::FETCH_OBJ);
   
	
   $headers = 'From: bobrchess.by@mail.ru';
   $message="Поздравляем с регистрацией на сайте bobrchess.of.by!\n\nВаш логин: ".$login."\nПароль : пароль указанный вами при регистрации.\nПерейдите    по ссылке, чтобы активировать ваш    аккаунт: http://bobrchess.of.by/php/activation.php?code=".$activation."\n\nС уважением, администрация сайта bobrchess.of.by";
   mail($email, "Добро пожаловать на сайт bobrchess.by!", $message); 
  //  mail($email, "Добро пожаловать на сайт bobrchess.by!", $message,$headers); 
   header("Location: http://bobrchess.of.by");
}

else 
{
    echo "Ошибка! Вы не зарегистрированы!";
}
