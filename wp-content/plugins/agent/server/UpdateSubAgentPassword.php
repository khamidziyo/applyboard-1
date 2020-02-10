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

                case 'validateOldPassword':
                    $id = $payload->userId;

                    if (empty($_POST['password'])) {
                        throw new Exception("Please enter your password");
                    }

                    if (empty($_POST['sub_agent_id'])) {
                        throw new Exception("Sub Agent Id is required");
                    }
                    $sub_agent_id = $_POST['sub_agent_id'];

                    // to get the old password...
                    $old_password = $_POST['password'];

                    //query to get the current password...
                    $sql = 'select password from agents where id=' . $sub_agent_id;

                    $data = $wpdb->get_results($sql);

                    $current_password = $data[0]->password;

                    // if password does not matches with the current password...
                    if (!password_verify($old_password, $current_password)) {
                        throw new Exception('Password is incorrect');
                    }
                    // if password is correct...
                    else {
                        // creating  a token...
                        $token = md5(rand(10000, 100000000000));

                        // update token in users table...
                        $wpdb->update('agents', ['forgot_password_token' => $token],
                            ['id' => $sub_agent_id, 'role' => '4']);
                        $data = ['token' => $token];

                        // returning the response...
                        $response = ['status' => Success_Code, 'message' => 'Password is correct', 'data' => $data];
                    }
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
