<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function verifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Staff::verifyUser($payload);
}

if (!empty($_GET['val'])) {
    try {
        if (verifyUser()) {
            switch ($_GET['val']) {
                case 'staffDashboard':

                    $id = $payload->userId;
                    $applications = $wpdb->get_results("select count(id) as total_application from applications");
                    $approve_application = $wpdb->get_results("select count(id) as approve_application from applications where review_by=" . $id . " && status='1'");
                    $pending_application = $wpdb->get_results("select count(id) as pending_application from applications where review_by=" . $id . " && status='0'");
                    $decline_application = $wpdb->get_results("select count(id) as decline_application from applications where review_by=" . $id . " && status='2'");
                    $review_application = $wpdb->get_results("select count(id) as review_application from applications where review_by=" . $id);

                    $response = ['status' => Success_Code, 'message' => 'Staff dashboard data fetch successfully',
                        'application' => $applications[0], 'approve_application' => $approve_application[0],
                        'decline_application' => $decline_application[0], 'pending_application' => $pending_application[0],
                        'review_application' => $review_application[0]];

                    break;

                default:
                    throw new Exception("No case Found");
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
