<form  action="../addNews.php?newsID={idNews}" method="post"> 
	<button class="btn">Редактировать</button>
</form>

<form  action="../php/deleteNews.php?newsID={idNews}" method="post"> 
	<button class="btn">Удалить</button>
</form>

<div>Просмотров новости: {countViews}</div>
<div>Уникальных посетителей новости: {countUniqualVisitors}</div>