<?php
/**
 * Plugin Name:       Course
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mukul
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:       Course
 * Domain Path:        languages
*/


include_once 'server/functions.php';


// function to view all courses

$view_arr = ['AddCourse', 'ViewAllCourse', 'ViewCourse'];
foreach ($view_arr as $view_name) {
    if(file_exists("views/" . $view_name . ".php")){
        echo "views/" . $view_name . ".php";

    }
    require "views/" . $view_name . ".php";
}
