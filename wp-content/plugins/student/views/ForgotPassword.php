<?php

function forgotPassword()
{
    ?>
   <div class="container">

   <form name="forgot_password_form" id="forgot_password_form">

   <input type='hidden' name='type' value=<?=!empty($_GET['type']) ? $_GET['type'] : ''?>>

   <label>Email:</label> <input type="text" name="u_mail" id="u_mail" placeholder="Please enter your email" >

   <input type="submit" class="btn btn-primary" id="forgot_pwd_btn" value="Submit">

   </form>


   </div>

   <script src="<?=student_asset_url?>/js/ForgotPassword.js"></script>

   <?php
}

add_shortcode('forgot_password', 'forgotPassword');
?>