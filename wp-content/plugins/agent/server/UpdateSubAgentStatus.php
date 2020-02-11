<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function agentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Agent::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {
        if (agentVerifyUser()) {

            switch ($_POST['val']) {
                case 'updateStatus':
                    $status = $_POST['status'];
                    $id = $_POST['id'];

                    // query to update the status...
                    $update_status = $wpdb->update('agents', ['status' => $status], ['id' => $id, 'role' => '4']);

                    // if status updates successfully...
                    if ($update_status) {
                        $response = ['status' => Success_Code, 'message' => 'Status Updated Successfully'];
                    } else {
                        throw new Exception("Status not updated due to internal server error.Try again later");
                    }
                    break;

                default:
                    throw new Exception("No match found");
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
