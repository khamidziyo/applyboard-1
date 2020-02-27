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

add_action("admin_menu", function () {
    // add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability,
    //  string $menu_slug, callable $function = '', int $position = null )
    add_submenu_page("options-general.php", "Admin", "Admin", "manage_options", "Admin", 'adminLogin');
});

$view_arr = ['AddStaff', 'AdminLogin', 'AdminProfile', 'AdminDashboard', 'ViewSchools', 'ChangePassword',
    'ViewCourses'];

foreach ($view_arr as $view_name) {
    include_once 'views/' . $view_name . '.php';
}
