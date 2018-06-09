<div class="modal fade login" id="loginModal">
         <div class="modal-dialog login animated">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Login with</h4>
               </div>
               <div class="modal-body">
                  <div class="box">
                     <div class="content">
                        <div class="social">
                           <a class="circle github" href="#">
                           <i class="fa fa-github fa-fw"></i>
                           </a>
                           <a id="google_login" class="circle google" href="#">
                           <i class="fa fa-google-plus fa-fw"></i>
                           </a>
                           <a id="facebook_login" class="circle facebook" href="http://bobrchess.of.by/vk-auth/firstStepToVK.php">
                           <i class="fa fa-vk fa-fw"></i>
                           </a>
                        </div>
                        <div class="division">
                           <div class="line l"></div>
                           <span>or</span>
                           <div class="line r"></div>
                        </div>
                        <div class="form loginBox">
                           <form method="post" html="{:multipart=>true}" action="php/authorization.php" accept-charset="UTF-8">
                              <input id="email" class="form-control" type="text" placeholder="Email" name="email" />
                              <input id="pass" class="form-control" type="text" placeholder="Password" name="pass" />
                              <input class="btn btn-default btn-login" type="submit" value="Войти" onclick="loginAjax()" />
                           </form>
                        </div>
                     </div>
                  </div>
                  <div class="box">
                     <div class="content registerBox" style="display:none;">
                        <div class="form">
                           <form method="post" html="{:multipart=>true}" data-remote="true" action="php/save_user.php" accept-charset="UTF-8">
                              <input id="login" class="form-control" type="text" placeholder="Login" name="login">
                              <input id="email" class="form-control" type="text" placeholder="Email" name="email">
                              <input id="password" class="form-control" type="password" placeholder="Password" name="password">
                              <input id="password_confirmation" class="form-control" type="password" placeholder="Repeat Password" name="passwordConfirmation">
                              <input class="btn btn-default btn-register" type="submit" value="Создать аккаунт" name="commit">
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <div class="forgot login-footer">
                     <span>Хотите
                     <a href="javascript: showRegisterForm();">создать учетную запись</a>
                     ?</span>
                  </div>
                  <div class="forgot register-footer" style="display:none">
                     <span>У вас уже есть учетная запись?</span>
                     <a href="javascript: showLoginForm();">Авторизоваться</a>
                  </div>
               </div>
            </div>
         </div>
      </div>