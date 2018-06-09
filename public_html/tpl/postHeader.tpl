<div class="container">
 <div class="header-bottom">
    <div class="type">
       <h5>Разделы</h5>
    </div>
    <span class="menu"></span>
    <div class="list-nav">
       <ul>
       	  |
          <li><a href="../../news.php">Новости</a></li>
          |
          <li><a href="../../gallery.php">Галерея</a></li>
          |
          <li><a href="../../publications.php">Публикации</a></li>
          |
          <li><a href="../../rules.php">Правила</a></li>
          |
          <li><a onclick="openLoginModal();" style="cursor: pointer;">Вход</a></li>
          |
       </ul>
    </div>
    <!-- script for menu -->
    <script>
       $( "span.menu" ).click(function() {
         $( ".list-nav" ).slideToggle( "slow", function() {
           // Animation complete.
         });
       });
    </script>
    <!-- script for menu -->
    <div class="clearfix"></div>
 </div>
</div>