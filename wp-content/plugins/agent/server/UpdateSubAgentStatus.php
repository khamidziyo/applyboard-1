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

function adminVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {

        switch ($_POST['val']) {
            case 'updateStatusByAgent':
                if (agentVerifyUser()) {

                    if (empty($_POST['id'])) {
                        throw new Exception("Sub Agent id is required");
                    }

                    $id = $_POST['id'];

                    updateSubAgentStatus($wpdb, $id);

                }
                break;

            case 'updateStatusByAdmin':
                if (adminVerifyUser()) {

                    if (empty($_POST['id'])) {
                        throw new Exception("Sub Agent id is required");
                    }

                    $id = $_POST['id'];

                    updateSubAgentStatus($wpdb, $id);
                }
                break;

            default:
                throw new Exception("No match found");
                break;
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

function updateSubAgentStatus($wpdb, $id)
{

    if (empty($_POST['status'])) {
        throw new Exception("Please select the status");
    }

    $status = $_POST['status'];

    // query to update the status...
    $update_status = $wpdb->update('agents', ['status' => $status], ['id' => $id, 'role' => '4']);

    // if status updates successfully...
    if ($update_status) {
        $response = ['status' => Success_Code, 'message' => 'Status Updated Successfully'];
    } else {
        throw new Exception("Status not updated due to internal server error.Try again later");
    }
    echo json_encode($response);
    exit;
}
echo json_encode($response);
