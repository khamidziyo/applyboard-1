<?php
function studentLogin()
{
    ?>



    <div class="container-fluid">
    <form name="student_login_form" id="student_login_form">
    <p>Email
    <input type="text" name="email" id="email" required>
    </p>
    <p>Password
    <input type="password" name="password" id="password" required>
    </p>
    <input type="submit" name="sign_in" id="sign_in" class="btn btn-primary" value="Sign In" >
      <img src="<?=content_url('plugins/student/assets/images/loading.gif')?>" id="load_img" width="200px" height="200px" style="display:none">
    </form>


    <a id="facebook-button" class="btn btn-block btn-social btn-facebook">
  <i class="fa fa-facebook"></i> Sign in with Facebook
    </a>

      <a id="google-button" class="btn btn-social btn-google">
      <i class="fa fa-google"></i> Sign in with Google
        </a>

      <p>
      <a href="http://localhost/wordpress/wordpress/index.php/student-sign-up/">Create Account</a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="http://localhost/wordpress/wordpress/index.php/forgot-password/" class="btn btn-primary">Forgot Password</a>

      </p>

    </div>


    <script type="text/javascript" src="<?=constant('student_asset_url') . "/js/StudentLogin.js"?>"></script>

    <?php
}
add_shortcode('student_login', 'studentLogin');

?>

