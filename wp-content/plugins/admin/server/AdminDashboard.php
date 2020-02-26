<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

// echo get_home_url();
// die;
if (file_exists(dirname(__DIR__, 2) . '/common/autoload.php')) {
    include_once dirname(__DIR__, 2) . '/common/autoload.php';
}

function verifyUser(){
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser($payload);
}

if (!empty($_GET['val'])) {
    if (verifyUser()) {
        try {
            switch ($_GET['val']) {

                case 'adminDashboard':
                    $schools = $wpdb->get_results("select count(id) as total_school from school");
                    $agents = $wpdb->get_results("select count(id) as total_agent from agents where role='3'");
                    $sub_agents = $wpdb->get_results("select count(id) as total_sub_agent from agents where role='4'");
                    $courses = $wpdb->get_results("select count(id) as total_courses from courses");
                    $staff = $wpdb->get_results("select count(id) as total_staff from staff");

                    $response = ['status' => Success_Code, 'schools' => $schools[0], 'agents' => $agents[0],
                        'sub_agents' => $sub_agents[0], 'courses' => $courses[0], 'staff' => $staff[0]];

                    break;

                default:
                    throw new Exception("No match found.Unauthorized Access");
                    break;
            }
        } catch (Exception $e) {
            $response = ['status' => Error_Code, 'message' => $e->getMessage()];

        }
    }

} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}

echo json_encode($response);
