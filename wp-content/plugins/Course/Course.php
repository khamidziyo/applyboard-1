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
/* License URI:       https://www.gnu.org/licenses/gpl-2.0.html*/
/* Text Domain:       Course
 * Domain Path:        languages

You should have received a copy of the GNU General Public License
along with {School Mangement}. If not, see {License URI}.
 */
/* permalink setting to my application route url */

// wp_enqueue_script( 'ava-test-js', plugins_url( 'assets/js/setting.js', __FILE__ ));

include_once 'server/functions.php';

/* start my application code(courses)   */


// function to view all courses



$view_arr = ['AddCourse','ViewAllCourse','ViewCourse'];
foreach ($view_arr as $view_name) {
    include_once "views/" . $view_name . ".php";
}




