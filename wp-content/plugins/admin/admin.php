<?php
/**
 * @package admin
 */
/*
Plugin Name: Applyboard Admin
Description: Administration Role
Version: 4.1.3
Author: Automattic
Text Domain: administartion
*/




add_action("admin_menu", function(){
    // add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position = null )
    add_submenu_page("options-general.php", "Admin", "Admin", "manage_options", "Admin",'adminLogin');
}); 

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

$view_arr=['AddStaff','AdminProfile','AdminDashboard','ViewSchools','ChangePassword','ViewCourses'];

foreach($view_arr as $view_name){
    include_once 'views/'.$view_name.'.php';
}
