<?php
	include 'php/general.php';
	$Obj = new Main();
	$Obj->init();
	
	class Main {
		
		private $template;
		private $args;
       	private $newsData;
		function parse() {
			foreach ($this->args as $key=>$value)
				$this->template = str_replace('{'.$key.'}',$value,$this->template);
		}
		public function GetContent($con,$name_table,$countRecord){
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
		private $arrayOfUserData = array();
      	function init() {
        	$this->arrayOfUserData = array("login"=>$_SESSION["login"]);         
          	include ("php/bd.php");
        }
		 public function generate(){
           $this->template = file_get_contents("tpl/addPhoto.tpl");	
           $this->args['head'] = file_get_contents("tpl/head.tpl"); 
           $this->args['header'] = file_get_contents("tpl/header.tpl"); 
           $this->args['postHeader'] = file_get_contents("tpl/postHeader.tpl"); 
			$this->args['siteTitle'] = file_get_contents("tpl/siteTitle.tpl"); 
            if($_SESSION["login"]==NULL){
				$this->args['header'] = file_get_contents("tpl/header.tpl");
				$this->args['postHeader'] = file_get_contents("tpl/postHeader.tpl");
            	$this->args['userBlock']="";
          	}
          	else{
            	$this->args['header'] = file_get_contents("tpl/headerUser.tpl"); 
            	$this->args['postHeader'] = file_get_contents("tpl/postHeaderUser.tpl"); 
            	$this->args['userBlock']="Ваш логин:".$this->arrayOfUserData["login"];
          	}
          	$this->args['loginArea'] = file_get_contents("tpl/loginArea.tpl"); 
          	$this->args['footer'] = file_get_contents("tpl/footer.tpl");
          	$this->args['footerLastNews'] = replaceArrayOfContent("tpl/footerLastNews.tpl",constr($this->GetContent(Connect(),"news",5)));
          	$this->parse();
			return $this->template;           
		}
      	public function replaceArrayOfContent($tplFile,$arrayOfData){
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
	};
	
	
	
	echo $Obj->generate();	