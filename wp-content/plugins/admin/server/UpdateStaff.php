<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function adminVerifyUser()
{
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {
        switch ($_POST['val']) {

            case 'updateStaffStatusByAdmin':
                if (adminVerifyUser()) {

                    if (empty($_POST['staff_id'])) {
                        throw new Exception("Staff id is required");
                    }

                    if (empty($_POST['status'])) {
                        throw new Exception("Please select the status");
                    }

                    $staff_id = base64_decode($_POST['staff_id']);
                 
                    $status = $_POST['status'];

                    $update_staff_res = $wpdb->update('staff', ['status' => $status, 'updated_at' => Date('Y-m-d h:i:s')], ['id' => $staff_id]);

                    if ($update_staff_res) {
                        $response = ['status' => Success_Code, 'message' => 'Status updated successfully'];
                    } else {
                        throw new Exception("Status not updated due to internal server error");
                    }
                }
                break;

            default:
                throw new Exception("No match found.Unauthorized Access");
                break;
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

echo json_encode($response);
