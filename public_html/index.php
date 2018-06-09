<?php
	include 'php/general.php';
	class Main {
      	
		function parse() {
			foreach ($this->args as $key=>$value)
				$this->template = str_replace('{'.$key.'}',$value,$this->template);
		}
		
		public function GetContent($con,$name_table,$countRecord,$type){
          //999 - рандомное выбрать
          // 0 - все
          // любое другое - количество, которое нужно выбрать #говнокодленьписать
          if ($type!=null){  
          	if ($type=="popular"){
          		$result = mysqli_query($con,"SELECT * FROM $name_table JOIN user on news.author = user.id ORDER BY news.countViews DESC LIMIT ".$countRecord); 
          	}else{
            	$result = mysqli_query($con,"SELECT * FROM $name_table JOIN user on news.author = user.id WHERE type = '$type' ORDER BY news.idNews DESC LIMIT ".$countRecord); 
          	}
          }
          else
          if ($countRecord==999){                     	 
             $result = mysqli_query($con,"SELECT * FROM $name_table WHERE id = $this->randomNumberOfQuote"); 
          }
          else
          {
            
          	if ($countRecord==0)
					$result = mysqli_query($con,"SELECT * FROM $name_table ORDER BY idNews"); 
            else             	
    	          	$result = mysqli_query($con,"SELECT * FROM $name_table ORDER BY idNews DESC LIMIT ".$countRecord);
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
			$this->template = file_get_contents("tpl/main.tpl");	
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
          	$this->args['loginArea'] = file_get_contents("tpl/loginArea.tpl"); 
          	$this->args['authorNews'] = replaceArrayOfContent("tpl/authorNews.tpl",constr($this->GetContent(Connect(),"news",4,1)));
          	$this->args['siteNews'] = replaceArrayOfContent("tpl/siteNews.tpl",constr($this->GetContent(Connect(),"news",4,5)));
          	$this->args['lastNews'] = replaceArrayOfContent("tpl/lastNews.tpl",constr($this->GetContent(Connect(),"news",3)));
          	$this->args['other'] = replaceArrayOfContent("tpl/other.tpl",constr($this->GetContent(Connect(),"news",1,"other")));
          	$this->args['clubNews'] = replaceArrayOfContent("tpl/clubNews.tpl",constr($this->GetContent(Connect(),"news",1,6)));
          	$this->args['popularNews'] = replaceArrayOfContent("tpl/popularNews.tpl",constr($this->GetContent(Connect(),"news",5,"popular")));
          	$this->args['footer'] = file_get_contents("tpl/footer.tpl");
          	$this->args['footerLastNews'] = replaceArrayOfContent("tpl/footerLastNews.tpl",constr($this->GetContent(Connect(),"news",5)));
          	$this->parse();
			return $this->template;
		}
	};
	$Obj = new Main();
	$Obj->init();
	echo $Obj->generate();
	