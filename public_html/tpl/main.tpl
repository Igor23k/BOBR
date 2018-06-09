<html>
   <head>
      {head}
   </head>
   <body>
      {header}
      {postHeader}
      <div class="container">
         <div class="content">
            <div class="col-md-7 content-left">
            	<h5 class="head">Последние новости</h5>
               {lastNews}
            </div>
            <div class="col-md-5 content-right">
               <div class="content-right-top">
                  <h5 class="head">Авторское</h5>
                  {authorNews}
               </div>
               <div class="editors-pic-grids">
                  <h5>Новости сайта</h5>
                  {siteNews}
               </div>
            </div>
            <div class="clearfix"></div>
            {other}
            {clubNews}
            <div class="col-md-5 content-right content-right-top">
               <h5 class="head">Популярное</h5>
                {popularNews}
            </div>
            <div class="clearfix"></div>
         </div>
      </div>
      {loginArea}
      {footer}
   </body>
</html>