<html>
	<head>
		{head}
	</head>
	<body>
		{header}
		{postHeader}
		<div class="container">
			<div class="content">
				
				<div id="wrapper">
                	<form action="../php/addPhoto.php" method="post" enctype="multipart/form-data" autocomplete="on">
                		<input type="hidden" name="myID" value="{id}" /> 
                		<h1>Добавить фото</h1> 
                            <p>
								<label>Выберите картинку. Изображение должно быть формата jpg, gif или png:<br></label>
								<input type="file" name="fupload">
							</p>
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