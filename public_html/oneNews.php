<?php
	include 'php/general.php';

	class Main {
      	
      	private $id;

      	private $arrayOfUserData = array();
		function parse() {
			foreach ($this->args as $key=>$value)
				$this->template = str_replace('{'.$key.'}',$value,$this->template);
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
		public function GetContent($con,$name_table,$countRecord){
			if ($countRecord==0)
				$result = mysqli_query($con,"SELECT * FROM $name_table"); 
          	else if ($countRecord==1)
            	$result = mysqli_query($con,"SELECT * FROM $name_table JOIN user on news.author = user.id WHERE idNews=$this->id");  
            	else
            		$result = mysqli_query($con,"SELECT * FROM $name_table ORDER BY idNews DESC LIMIT ".$countRecord);
			$additionalArray = array(); 
			while($row = mysqli_fetch_assoc($result)){ 
				$additionalArray[] = $row; 
			}; 
			return $additionalArray; 
		}
      	function init() {
        	$this->arrayOfUserData = array("login"=>$_SESSION["login"]);  
          	$this->id = $_GET["newsID"];
          	$this->addVisitor(Connect(),"news");
          	$this->addUniqualVisitor(Connect(),"ipusernews",$_SERVER["REMOTE_ADDR"]);
        }
		function generate(){
          $this->template = file_get_contents("tpl/oneNews.tpl");
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
	      	if (($_SESSION['permission'] & U_ALL) == U_ALL) {
	         	$this->args['editNewsLink'] =  replaceArrayOfContent("tpl/editNewsLink.tpl",$this->constr($this->GetContent(Connect(),"news",1)));
	      	}
	        else{
	          	$this->args['editNewsLink'] ='';
	        }      
	        $this->args['loginArea'] = file_get_contents("tpl/loginArea.tpl"); 
	      	$this->args['newsBlock'] = replaceArrayOfContent("tpl/oneNewsBlock.tpl",$this->constr($this->GetContent(Connect(),"news",1)));
	      	$this->args['comments'] = file_get_contents("tpl/comments.tpl");
	      	$this->args['footer'] = file_get_contents("tpl/footer.tpl");
	      	$this->args['footerLastNews'] = replaceArrayOfContent("tpl/footerLastNews.tpl",$this->constr($this->GetContent(Connect(),"news",5)));
	     	$this->args['id'] = $this->id;
			$this->parse();
			return $this->template;
		}
		
		function addVisitor($con,$name_table) {
 			mysqli_query($con,"UPDATE $name_table SET countViews = countViews + 1 WHERE idNews = $this->id"); 
        }
        function addUniqualVisitor($con,$name_table,$ip) {
        	$result = mysqli_query($con,"SELECT * FROM $name_table WHERE ip = '$ip' AND idNews = $this->id");  
			$additionalArray = array(); 
			while($row = mysqli_fetch_assoc($result)){ 
				$additionalArray[] = $row; 
			}; 
			if (count($additionalArray) == 0){
				mysqli_query($con,"INSERT INTO $name_table (`id`, `ip`, `idNews`) VALUES ('0','$ip', $this->id)");
			}
			$countUniqualVisitors = $this->getCountUniqualVisitor(Connect(),"ipusernews");
			mysqli_query($con,"UPDATE news SET countUniqualVisitors = $countUniqualVisitors WHERE idNews = $this->id"); 
        }
        function getCountUniqualVisitor($con,$name_table) {
 			$result = mysqli_query($con,"SELECT * FROM $name_table WHERE idNews = $this->id"); 
 			$additionalArray = array(); 
			while($row = mysqli_fetch_assoc($result)){ 
				$additionalArray[] = $row; 
			}; 
			return count($additionalArray);
        }
	};
	$Obj = new Main();
	$Obj->init();
	echo $Obj->generate();