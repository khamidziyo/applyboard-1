<?php
   /**
    * Plugin Name:       Student
    * Plugin URI:        https://example.com/plugins/the-basics/
    * Description:       Handle the basics with this plugin.
    * Version:           1.10.3
    * Requires at least: 5.2
    * Requires PHP:      7.2
    * Author:            Mukul
    * Author URI:        https://author.example.com/
    * License:           GPL v2 or later
    /* License URI:       https://www.gnu.org/licenses/gpl-2.0.html*/
    /* Text Domain:       Student
    * Domain Path:        languages
   */
 


  include_once(dirname(__DIR__,1)."/common/constants.php");


   function studentSignUp(){
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

      
    </div>
  

    <script src="https://cdn.rawgit.com/oauth-io/oauth-js/c5af4519/dist/oauth.js"></script>
    <script src="<?=constant('student_asset_url')."/js/StudentSignup.js"?>"></script>
     <?php
   }


   function studentLogin(){
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


    <p>
     <a id="facebook-button" class="btn  btn-social btn-facebook">
      <i class="fa fa-facebook"></i> Sign in with Facebook
        </a>
      <a id="google-button" class="btn btn-social btn-google">
      <i class="fa fa-google"></i> Sign in with Google
        </a>
      </p>
      <p>
      <a href="http://localhost/wordpress/wordpress/index.php/student-sign-up/">Create Account</a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="http://localhost/wordpress/wordpress/index.php/forgot-password/" class="btn btn-primary">Forgot Password</a>

      </p>

    </div>
    <script type="text/javascript" src="<?= constant('student_asset_url')."/js/StudentLogin.js"?>"></script>

    <?php
   }



   add_shortcode('student_sign_up','studentSignUp');

   add_shortcode('student_login','studentLogin');
   
   // views array to load all the views...
   $views=['StudentDashboard','EligibleProgram','StudentProfile','ForgotPassword',
   'ResetPassword','ChangePassword','MyApplications'];

   foreach($views as $view_name){
      include_once "views/".$view_name.".php";
   }
  

