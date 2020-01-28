<?php
/**
 * @package School
 */
/*
Plugin Name: School
Description: School profile
Version: 4.1.3
Author: Automattic
Text Domain: school
 */

add_action("admin_menu", function () {
    // add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position = null )
    add_submenu_page("options-general.php", "Add School", "Add School", "manage_options", "Add School", 'addSchool');
});

$view_arr = ['SchoolLogin', 'SchoolDashboard','SchoolProfile', 'AddSchool',
 'VerifySchool', 'ViewSchool','Notifications','UserDetail'];
foreach ($view_arr as $view_name) {
    include_once "views/" . $view_name . ".php";
}

include_once dirname(__DIR__, 1) . "/common/constants.php";
