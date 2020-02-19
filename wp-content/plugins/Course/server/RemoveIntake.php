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
    return School::verifyUser($payload);
}

if (!empty($_POST['val'])) {

    try {
        if (verifyUser()) {

            switch ($_POST['val']) {

                case 'removeIntake':

                    if (empty($_POST['intake_id'])) {
                        throw new Exception("Intake id is required");
                    }

                    $intake_id = base64_decode($_POST['intake_id']);
                   
                    $delete_res = $wpdb->query("delete from course_intake where id=" . $intake_id);
                    
                    if ($delete_res) {
                        $response = ['status' => Success_Code, 'message' => "Intake deleted successfully"];
                    } else {
                        throw new Exception("Course intake not deleted due to internal server error.Try again.");
                    }
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
