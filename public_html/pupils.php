<?php
	include 'php/general.php';
	class Main {
		
		function parse() {
			foreach ($this->args as $key=>$value)
				$this->template = str_replace('{'.$key.'}',$value,$this->template);
		}
		public function GetContent($con,$name_table){
          	$result = mysqli_query($con,"SELECT * FROM $name_table WHERE category = 3");
			$additionalArray = array(); 
			while($row = mysqli_fetch_assoc($result)){ 
			$additionalArray[] = $row; 
			}; 
			return $additionalArray; 
		}
		public function GetContentNews($con,$name_table,$countRecord){//#Говнокодюшко. Но оно ведь работает, и менять я это не планирую
			if($countRecord != 0){
				$result = mysqli_query($con,"SELECT * FROM $name_table JOIN user on news.author = user.id ORDER BY idNews DESC LIMIT ".$countRecord); 
			}else{
				$result = mysqli_query($con,"SELECT * FROM $name_table JOIN user on news.author = user.id ORDER BY idNews DESC"); 
			}
			$additionalArray = array(); 
			while($row = mysqli_fetch_assoc($result)){ 
				$additionalArray[] = $row; 
			}; 
			return $additionalArray; 
		}
      	function init() {
        	$this->arrayOfUserData = array("login"=>$_SESSION["login"]);   
        }
		 public function generate(){
			$this->template = file_get_contents("tpl/pupils.tpl");
			$this->args['head'] = file_get_contents("tpl/head.tpl"); 
			$this->args['siteTitle'] = file_get_contents("tpl/siteTitle.tpl"); 
			$this->args['header'] = file_get_contents("tpl/header.tpl");
            if($_SESSION["login"]==NULL){
				$this->args['postHeader'] = file_get_contents("tpl/postHeader.tpl");
            	$this->args['userBlock']="";
          	}
          	else{
            	$this->args['postHeader'] = file_get_contents("tpl/postHeaderUser.tpl"); 
            	$this->args['userBlock']="Ваш логин:".$this->arrayOfUserData["login"];
          	}
          	$this->args['pupilsImage'] = replaceArrayOfContent("tpl/pupilsImage.tpl",constr($this->GetContent(Connect(),"image",3)));
          	$this->args['loginArea'] = file_get_contents("tpl/loginArea.tpl"); 
          	$this->args['footer'] = file_get_contents("tpl/footer.tpl");
          	$this->args['footerLastNews'] = replaceArrayOfContent("tpl/footerLastNews.tpl",constr($this->GetContentNews(Connect(),"news",5)));
          	$this->parse();
			return $this->template;
		}
	};
	$Obj = new Main();
	$Obj->init();
	echo $Obj->generate();
	