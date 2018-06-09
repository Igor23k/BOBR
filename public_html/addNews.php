<?php
	include 'php/general.php';
	
	$Obj = new Main();
	$Obj->init();
	
	class Main {
       	private $newsData;
       	private $newsId;
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
		public function constr($publications){	
			$arrayData = array();		
			foreach ($publications as $ValuesFromDataArr)
			{	
				foreach ($ValuesFromDataArr as $key=>$value)
				{	
					$additionalArrForKeys[$key]=$value;
				}
				array_push($arrayData,$additionalArrForKeys);			
			}
			return $arrayData;	
		}
		private $arrayOfUserData = array();
      	function init() {
        	$this->arrayOfUserData = array("login"=>$_SESSION["login"]);         
          	include ("php/bd.php");
          	$newsId=$_GET["newsID"];
          	if($newsId!=0){
            	$result = $pdo->prepare("SELECT * FROM news WHERE idNews = $newsId");
            	$result->execute();			
            	$myrow = $result->fetch(PDO::FETCH_OBJ);
            	$this->newsData = array("title"=>$myrow->title,"subtitle"=>$myrow->subtitle,"text"=>$myrow->text);
          	}
        }
		 public function generate(){
           $this->template = file_get_contents("tpl/addNews.tpl");	
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
          	if($newsId==0){
          		$this->args['addNewsAdditionalTools'] = file_get_contents("tpl/addNewsAdditionalTools.tpl");
          	}
          	$this->args['loginArea'] = file_get_contents("tpl/loginArea.tpl"); 
          	$this->args['footer'] = file_get_contents("tpl/footer.tpl");
            $this->args['footerLastNews'] = replaceArrayOfContent("tpl/footerLastNews.tpl",constr($this->GetContent(Connect(),"news",5)));
            $this->args['title'] = $this->newsData["title"];
            $this->args['subtitle'] = $this->newsData["subtitle"];
            $this->args['text'] = $this->newsData["text"];
            $this->args['id'] = $_GET["newsID"];
			$this->parse();
			return $this->template;           
		}
     
	};
	
	
	
	echo $Obj->generate();	