<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

// function to verify user...

function verifyUser()
{
    global $payload;

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser($payload);
}

if (!empty($_GET['val'])) {
    try {

        switch ($_GET['val']) {
            case 'studentDashboard':
                // if token is verfied...
                if (verifyUser()) {
                    $id = $payload->userId;

                    $applications = $wpdb->get_results("select count(id) as applications from applications where student_id=" . $id);
                    $approve = $wpdb->get_results("select count(id) as application_approve from applications where status='1' && student_id=" . $id);
                    $decline = $wpdb->get_results("select count(id) as application_decline from applications where status='2' && student_id=" . $id);
                    $pending = $wpdb->get_results("select count(id) as application_pending from applications where status='0' && student_id=" . $id);

                    $response = ['status' => Success_Code, 'message' => 'Student Dashboard Data fetched successfully',
                        'total_application' => $applications[0]->applications,
                        'application_approve' => $approve[0]->application_approve,
                        'application_decline' => $decline[0]->application_decline,
                        'application_pending' => $pending[0]->application_pending,
                    ];
                }
                break;

            default:
                throw new Exception("No match Found");
                break;
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}
echo json_encode($response);
