<html>
	<head>
		{head}
		<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
	</head>
	<body>
		{header}
		{postHeader}
		<div class="container">
			<div class="content">
				<div class="col-md-12 content-right">
				<div class="content-right-top">
					<h5 class="head">Новости</h5>	
												
												
												
												<div id="wrapper">
							                    	
							                    	<form action="php/addNews.php" method="post" enctype="multipart/form-data" autocomplete="on">
							                    		<input type="hidden" name="myID" value="{id}" /> 
							                    		<h1>Редактировать</h1> 
							                                <p> 
							                                    <label for="username" class="uname" data-icon="u" >Заголовок</label>
							                                    <input id="username" name="title" required="required" type="text" value="{title}"/>
							                                </p>

								                            <p> 
								                                <label for="username" class="uname" data-icon="u" >Подзаголовок</label>
								                                <input id="username" name="subtitle" placeholder="Не обязательно" type="text" value="{subtitle}"/>
								                            </p>
								                            <label for="username" class="uname" data-icon="u" >Тема новости:</label>
															<select name="theme">
															  <option>tournament</option>
															  <option>site</option>
															  <option>club</option>
															  <option>world</option>
															  <option>author</option>
															  <option>other</option>
															</select>
															<p>
																<label>Выберите главную картинку. Изображение должно быть формата jpg, gif или png:<br></label>
																<input type="file" name="fupload">
																Использовать дефолтную картинку вместо своей <input id="checkBox" name="checkBox" type="checkbox">
															</p>

												        <textarea name="msg" id="editor1" rows="10" cols="80">
												            {text}
												        </textarea>
												        <script>
												            CKEDITOR.replace( 'editor1' );
												        </script>
												        <p class="login button"> 
							                                    <input type="submit" value="Добавить" /> 
														</p>
												    </form>
											
							                       
                    							</div>
                    
                    
                    
                    
					</div>
				</div>
							
			</div>
		</div>
		{loginArea}
		{footer}
	</body>
</html>