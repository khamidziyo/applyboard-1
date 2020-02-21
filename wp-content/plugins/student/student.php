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



// views array to load all the views...
$views = ['StudentSignup', 'StudentLogin', 'StudentDashboard', 'EligibleProgram', 'StudentProfile',
    'ForgotPassword', 'ResetPassword', 'MyApplications', 'StudentDetail'];

foreach ($views as $view_name) {
    include_once "views/" . $view_name . ".php";
}
