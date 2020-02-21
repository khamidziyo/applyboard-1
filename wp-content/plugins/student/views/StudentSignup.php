<?php
function studentSignUp()
{
    ?>
    <div class="container-fluid">
    <form name="student_reg_form" id="student_reg_form">

    <center>
    <h2>Student Registration</h2>
    <p>Email
    <input type="email" name="email" id="email" required>
    </p>
    <p>Password
    <input type="password" name="password" id="password" required>
    </p>
    <p>Confirm Password
    <input type="password" name="con_password" id="con_password" required>
    </p>
      <input type="submit" name="sign_up" id="sign_up" value="Sign Up" >
      <img src="<?=content_url('plugins/student/assets/images/loading.gif')?>" id="load_img" width="200px" height="200px" style="display:none">
    </center>

      </form>

      <p>
     <a id="facebook-button" class="btn  btn-social btn-facebook">
      <i class="fa fa-facebook"></i> Sign up with Facebook

      <a id="google-button" class="btn btn-social btn-google">
      <i class="fa fa-google"></i> Sign up with Google
        </a>
        

    </div>


    <script src="https://cdn.rawgit.com/oauth-io/oauth-js/c5af4519/dist/oauth.js"></script>
    <script src="<?=constant('student_asset_url') . "/js/StudentSignup.js"?>"></script>
     <?php
}

add_shortcode('student_sign_up', 'studentSignUp');

?>