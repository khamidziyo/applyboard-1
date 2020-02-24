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

if (!empty($_GET['val'])) {

    try {
        switch ($_GET['val']) {
            case 'getAgentProfile':
                if (agentVerifyUser()) {
                    $id = $payload->userId;
                    $data = $wpdb->get_results("select * from agents where id=" . $id . " && role='3' ");
                    $response = ['status' => Success_Code, 'message' => 'Profile Fetched Successfully', 'data' => $data[0]];
                }
                break;

            case 'getAgentProfileByAdmin':
                if (adminVerifyUser()) {

                    if (empty($_GET['agent_id'])) {
                        throw new Exception("Agent id is required");
                    }

                    $id = base64_decode($_GET['agent_id']);

                    $data = $wpdb->get_results("select * from agents where id=" . $id . " && role='3' ");
                    $response = ['status' => Success_Code, 'message' => 'Profile Fetched Successfully', 'data' => $data[0]];

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
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is Required"];
}

echo json_encode($response);
