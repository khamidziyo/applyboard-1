<?php

function forgotPassword(){
   ?>
   <div class="container-fluid">
   <form>
   <p>Email: <input type="email" name="email" id="email" required email>
   </p>
   <p><input type="submit" class="btn btn-primary" id="forgot_pwd_btn" value="Submit"></p>
   </form>

   </div>
   
   <img src="<?=content_url('plugins/student/assets/images/loading.gif')?>" id="load_img" width="200px" height="200px" style="display:none">
   <script src="<?=student_asset_url?>/js/ForgotPassword.js"></script>

   <?php
}

add_shortcode('forgot_password','forgotPassword');
?>