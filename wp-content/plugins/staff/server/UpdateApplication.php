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

if (!empty($_POST['val'])) {
    if (verifyUser()) {
        try {
            switch ($_POST['val']) {
                case 'markApplicationReview':

                    if (empty($_POST['app_id'])) {
                        throw new Exception("Application id is required");
                    }
                    $id = $payload->userId;

                    $app_id = $_POST['app_id'];

                    $update_res = $wpdb->update('applications', ['is_reviewed' => '1', 'review_by' => $id,
                        'updated_at' => Date('Y-m-d h:i:s')], ['id' => $app_id]);

                    if ($update_res) {
                        $response = ['status' => Success_Code, 'message' => "Application Reviewed Successfully"];
                    } else {
                        throw new Exception("Unable to update the status of application due to internal sever error");
                    }

                    break;

                case 'updateStatus':

                    // if the application id is missing...
                    if (empty($_POST['app_id'])) {
                        throw new Exception("Application id is required");
                    }

                    // if the application status is missing...
                    if (empty($_POST['status'])) {
                        throw new Exception("Please select the application status");
                    }

                    $app_id = $_POST['app_id'];
                    $status = $_POST['status'];

                    // update query to update the application...
                    $update_app_res = $wpdb->update('applications', ['status' => $status,
                        'updated_at' => Date('Y-m-d h:i:s')], ['id' => $app_id]);

                    // if application status updates successfully...
                    if ($update_app_res) {
                        $response = ['status' => Success_Code, 'message' => 'Application status updated successfully'];
                    }

                    // if application status not updated successfully...
                    else {
                        throw new Exception("Application not updated due to internal server error");
                    }
                    break;

                // if no case matches...
                default:
                    throw new Exception("No case matches");
                    break;

            }
        } catch (Exception $e) {
            $response = ['status' => Error_Code, 'message' => $e->getMessage()];
        }

    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

// return the json response...
echo json_encode($response);
