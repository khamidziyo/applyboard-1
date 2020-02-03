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
    return Admin::verifyUser($payload);
}

if (!empty($_POST)) {
    $response = [];
    try {
        if (verifyUser()) {

            $id = $payload->userId;

            $sql = "select id,email,image from users where id=" . $id . " && role='2'";

            $user = $wpdb->get_results($sql);

            if (!empty($user)) {
                $response = ['status' => Success_Code, 'message' => "Profile fetched Successfully", 'data' => $user[0]];
            } else {
                throw new Exception("No user found");
            }
        }

    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];

    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}

echo json_encode($response);
