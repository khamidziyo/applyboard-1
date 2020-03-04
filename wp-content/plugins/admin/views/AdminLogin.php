<?php
function adminLogin()
{
    ?>
     <div class="container-fluid">
    <form name="admin_login_form" id="admin_login_form">
    <p>Email
    <input type="text" name="email" id="email" required email>
    </p>
    <p>Password
    <input type="password" name="password" id="password" required>
    </p>
    <input type="submit" name="sign_in" id="sign_in_btn" class="btn btn-primary" value="Sign In" >
    </form>
    
    <a href="<?=base_url?>forgot-password?type=admin" class="btn btn-primary">Forgot Password</a>
    </div>
    <script src="<?=constant('admin_asset_url') . "/js/AdminLogin.js"?>"></script>

    <?php
}

add_shortcode('admin_login', 'adminLogin');