<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function schoolVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return School::verifyUser($payload);
}

if (!empty($_GET['val'])) {
    try {
        if (schoolVerifyUser()) {
            switch ($_GET['val']) {

                case 'schoolDashboard':
                    $id = $payload->userId;
                    // to get all the total courses of school...
                    $total_courses = $wpdb->get_results("select count(id) as total_courses from courses where school_id=" . $id);

                    // to get all the students who has applied to the courses of the school...
                    $total_students = $wpdb->get_results("SELECT DISTINCT COUNT(student_id) as total_students FROM applications WHERE school_id= " . $id);

                    // total applications submitted to the school...
                    $total_applications = $wpdb->get_results("select count(id) as total_application from applications where school_id=" . $id);

                    // total applications approved by the school...
                    $total_application_approve = $wpdb->get_results("select count(id) as total_app_application from applications where school_id=" . $id . " && status='1'");

                    // total applications declined by the school...
                    $total_application_decline = $wpdb->get_results("select count(id) as total_dec_application from applications where school_id=" . $id . " && status='2'");

                    // total applications that are pending...
                    $total_application_pending = $wpdb->get_results("select count(id) as total_pen_application from applications where school_id=" . $id . " && status='0'");

                    $response = ['status' => Success_Code, 'message' => 'School Dashboard', 
                    'students' => $total_students[0],'courses' => $total_courses[0], 
                    'applications' => $total_applications[0],
                        'approve_application' => $total_application_approve[0], 
                        'decline_application' => $total_application_decline[0],
                        'pending_application' => $total_application_pending[0]];

                    break;

                default:
                    throw new Exception("No match Found");
                    break;
            }
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

echo json_encode($response);
