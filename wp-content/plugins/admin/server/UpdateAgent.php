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
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    if (verifyUser()) {
        try {
            switch ($_POST['val']) {
                case 'updateAgentStatus':

                    if (empty($_POST['agent_id'])) {
                        throw new Exception("Agent id is required");
                    }

                    if (empty($_POST['status'])) {
                        throw new Exception("Please select the status");
                    }

                    $agent_id = base64_decode($_POST['agent_id']);
                    $status = $_POST['status'];

                    $update_agent_res = $wpdb->update('agents', ['status' => $status, 'updated_at' => Date('Y-m-d h:i:s')], ['id' => $agent_id]);

                    if ($update_agent_res) {
                        $response = ['status' => Success_Code, 'message' => 'Status updated successfully'];
                    } else {
                        throw new Exception("Status not updated due to internal server error");
                    }
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
