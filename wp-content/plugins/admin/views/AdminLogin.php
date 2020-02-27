<?php
function adminLogin(){
    ?>
     <div class="container-fluid">
    <form name="admin_login_form" id="admin_login_form">
    <p>Email
    <input type="text" name="email" id="email" required email>
    </p>
    <p>Password
    <input type="password" name="password" id="password" required>
    </p>
    <input type="submit" name="sign_in" id="sign_in" class="btn btn-primary" value="Sign In" >
      <img src="<?=content_url('plugins/admin/assets/images/loading.gif')?>" id="load_img" width="200px" height="200px" style="display:none">
    </form>
    </div>
    <script src="<?=constant('admin_asset_url')."/js/AdminLogin.js"?>"></script>

    <?php
}

add_shortcode('admin_login','adminLogin');