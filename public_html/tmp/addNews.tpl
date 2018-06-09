<html><!-- наверн уже не нужен этот файл-->
	<head>
		{head}
	</head>
	<body>
		{header}
		{postHeader}
		<div class="container">
			<div class="content">
				
				<div id="wrapper">
                	<form action="../php/addNews.php" method="post" enctype="multipart/form-data" autocomplete="on">
                		<input type="hidden" name="myID" value="{id}" /> 
                		<h1>Добавить новость</h1> 
                            <p> 
                                <label for="username" class="uname" data-icon="u" >Заголовок</label>
                                <input id="username" name="title" required="required" type="text" value=""/>
                            </p>
                            <p> 
                                <label for="username" class="uname" data-icon="u" >Подзаголовок</label>
                                <input id="username" name="subtitle" required="required" type="text" value=""/>
                            </p>
                            <label for="username" class="uname" data-icon="u" >Тема новости:</label>
                            <select name="theme">
							  <option>tournament</option>
							  <option>site</option>
							  <option>club</option>
							  <option>world</option>
							  <option>author</option>
							  <option>site</option>
							  <option>other</option>
							</select>
                            <p>
								<label>Выберите картинку. Изображение должно быть формата jpg, gif или png:<br></label>
								<input type="file" name="fupload">
							</p>
				        <textarea name="msg" id="editor1" rows="10" cols="80"></textarea>
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
		{loginArea}
		{footer}
	</body>
</html>