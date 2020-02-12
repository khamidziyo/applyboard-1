<?php
/**
 * Plugin Name:       Staff
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mukul
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
/* License URI:       https://www.gnu.org/licenses/gpl-2.0.html*/
/* Text Domain:       Staff
 * Domain Path:        languages
 **/
$view_arr = ['StaffLogin','StaffDashboard','StaffProfile'];

foreach ($view_arr as $view_name) {
    include_once 'views/' . $view_name . '.php';
}
