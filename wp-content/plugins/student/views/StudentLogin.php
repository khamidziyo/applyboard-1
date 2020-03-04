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
    <input type="submit" name="sign_in" id="sign_in_btn" class="btn btn-primary" value="Sign In" >
    </form>


    <a id="facebook-button" class="btn btn-block btn-social btn-facebook">
  <i class="fa fa-facebook"></i> Sign in with Facebook
    </a>

      <a id="google-button" class="btn btn-social btn-google">
      <i class="fa fa-google"></i> Sign in with Google
        </a>

      <p>
      <a href="<?=base_url?>student-sign-up/">Create Account</a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="<?=base_url?>forgot-password?type=student" class="btn btn-primary">Forgot Password</a>

      </p>

    </div>


    <script type="text/javascript" src="<?=constant('student_asset_url') . "/js/StudentLogin.js"?>"></script>

    <?php
}
add_shortcode('student_login', 'studentLogin');

