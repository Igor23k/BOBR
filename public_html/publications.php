<?php
	include 'php/general.php';
	include('php/bdConnectMySQL.php');
	class Main {
		
      	private $arrayOfUserData;
      	private $quantity=9;
        private $limit=5;
        private $pages;
      	private $page;
      	private $list;
      	private $this;
      	private $imageCategory=2;
		function parse() {
			foreach ($this->args as $key=>$value)
				$this->template = str_replace('{'.$key.'}',$value,$this->template);
		}
	
		public function GetContent($con,$name_table){
          	$result = mysqli_query($con,"SELECT * FROM $name_table WHERE category = 2
                      LIMIT $this->quantity OFFSET $this->list;");
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
      public function init(){
      	$this->arrayOfUserData=array("login"=>$_SESSION["login"]);
        $this->page=$_GET["page"];
        if(!is_numeric($this->page)) 
          	$this->page=1;
        if ($this->page<1) 
        	$this->page=1;
        $result2 = mysql_query("SELECT * FROM image WHERE category = 2");
        $num = mysql_num_rows($result2);
        $this->pages =$num/$this->quantity;
        $this->pages = ceil($this->pages);
        $this->pages++; 
		if ($this->page>$this->pages) 
        	$this->page = 1;
        if (!isset($this->list)) 
       		$this->list=0;
        $this->list=--$this->page*$this->quantity;        
      }
		function generate(){
			$this->template = file_get_contents("tpl/gallery.tpl");	
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
           	if (($_SESSION['permission'] & U_ALL) == U_ALL) {
                 $this->args['addPhoto'] =  file_get_contents("tpl/addPhotoLink.tpl");
            }
            else{
              	$this->args['addPhoto'] ="";
            }                	
          	$this->args['image'] = replaceArrayOfContent("tpl/galleryImage.tpl",constr($this->GetContent(Connect(),"image")));
			
          	$linksAgo='<a href="' . $_SERVER['SCRIPT_NAME'] . '?page=1">Первая</a> &nbsp; 
<a href="' . $_SERVER['SCRIPT_NAME'] . '?page=' . $this->page . '">Назад </a> &nbsp;';
         	if ($this->page >= 1)
              $this->args['linksAgo'] = $linksAgo;
          	else
              $this->args['linksAgo'] = "";
          	$this->this = $this->page+1;
          
            // Узнаем с какой ссылки начинать вывод
            $start = $this->this-$limit;
            
            // Узнаем номер последней ссылки для вывода
            $end = $this->this+$this->limit;
            
            for ($j = 1; $j<$this->pages; $j++) {
            	
             
                // Выводим ссылки только в том случае, если их номер больше или равен
                // начальному значению, и меньше или равен конечному значению
                if ($j>=$start && $j<=$end) {
            
                    // Ссылка на текущую страницу выделяется жирным
                    if ($j==($this->page+1)) $linksCurrent .= '<a href="' . $_SERVER['SCRIPT_NAME'] . 
                    '?page=' . $j . '"><strong style="color: #df0000">' . $j . 
                    '</strong></a> &nbsp; ';
            
                    // Ссылки на остальные страницы
                    else $linksCurrent.= '<a href="' . $_SERVER['SCRIPT_NAME'] . '?page=' . 
                    $j . '">' . $j . '</a> &nbsp; ';
                }
            }
          	$this->args['linksCurrent'] = $linksCurrent;
          	$linksForward='<a href="' . $_SERVER['SCRIPT_NAME'] . '?page=' . ($page+2) . '"> Вперед</a> &nbsp;
<a href="' . $_SERVER['SCRIPT_NAME'] . '?page=' . ($j-1) . '">Последняя</a> &nbsp;';
          	if ($j>$this->page && ($this->page+2)<$j)
              	$this->args['linksForward'] = $linksForward;
          	else
              $this->args['linksForward'] = "";
			$this->args['footer'] = file_get_contents("tpl/footer.tpl");
          	$this->args['footerLastNews'] = replaceArrayOfContent("tpl/footerLastNews.tpl",constr($this->GetContentNews(Connect(),"news",5)));
          	$this->parse();
			return $this->template;
		}
	};
	$Obj = new Main();
	$Obj->init();
	echo $Obj->generate();