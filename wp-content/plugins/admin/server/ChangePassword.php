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
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser($payload);
}

function agentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Agent::verifyUser($payload);
}

function studentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Student::verifyUser($payload);
}

function subAgentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return SubAgent::verifyUser($payload);
}

function staffVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Staff::verifyUser($payload);
}

if (!empty($_POST['val'])) {

    try {

        $require_arr = ['password', 'confirm_password', 'token', 'role'];

        foreach ($require_arr as $form_input) {

            // if any form key does not exist in the request...
            if (!array_key_exists($form_input, $_POST)) {
                throw new Exception($form_input . " is missing in the request");
            }

            // if any form input value is empty...
            if (empty($_POST[$form_input])) {
                throw new Exception("Please enter the " . $form_input);
            }
        }

        if (strlen($_POST['password']) < 6) {
            throw new Exception('Password should not be less than six characters');
        }

        if ($_POST['password'] != $_POST['confirm_password']) {
            throw new Exception('Password and confirm password are not same');
        }

        // get the forgot password token...
        $token = $_POST['token'];

        switch ($_POST['val']) {
            case 'changePassword':

                switch ($_POST['role']) {
                    case '1':
                        if (studentVerifyUser()) {
                            $id = $payload->userId;

                            // query to update the new password...
                            $update = $wpdb->update('users', ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)],
                                ['forgot_password_token' => $token, 'id' => $id]);
                        }
                        break;

                    // if logged in user is admin...
                    case '2':
                        if (adminVerifyUser()) {
                            switch ($_POST['type']) {

                                case 'updateAgentPasswordByAdmin':

                                    $agent_id = base64_decode($_POST['agent_id']);

                                    // query to update the new password...
                                    $update = $wpdb->update('agents', ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)],
                                        ['forgot_password_token' => $token, 'id' => $agent_id]);

                                    break;

                                case 'updateAdminPassword':
                                    $id = $payload->userId;

                                    // query to update the new password...
                                    $update = $wpdb->update('users', ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)],
                                        ['forgot_password_token' => $token, 'id' => $id]);
                                    break;

                            }
                        }

                        break;

                    // if the logged in user is agent...
                    case '3':
                        $role = $_POST['role'];
                        if (agentVerifyUser()) {
                            $id = $payload->userId;

                            // when agent updates the password of sub agent...
                            if (!empty($_POST['sub_agent_id'])) {

                                // get the sub agent id...
                                $sub_agent_id = base64_decode($_POST['sub_agent_id']);

                                // query to update the new password...
                                $update = $wpdb->update('agents', [
                                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                                    'updated_at' => Date('Y-m-d h:i:s'),
                                ],
                                    ['forgot_password_token' => $token, 'id' => $sub_agent_id, 'role' => '4',
                                    ]);
                            } else {
                                // query to update the new password for agent...

                                $update = $wpdb->update('agents', [
                                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                                    'updated_at' => Date('Y-m-d h:i:s'),
                                ],
                                    ['forgot_password_token' => $token, 'id' => $id, 'role' => $role,
                                    ]);
                            }

                        }
                        break;

                    // if the logged in user is sub agent...
                    case '4':
                        $role = $_POST['role'];

                        if (subAgentVerifyUser()) {
                            $id = $payload->userId;
                            // query to update the new password...
                            $update = $wpdb->update('agents',
                                ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                                    'updated_at' => Date('Y-m-d h:i:s')],
                                ['forgot_password_token' => $token, 'id' => $id, 'role' => $role]);
                        }
                        break;

                    // if the logged in user is staff...
                    case '5':
                        if (staffVerifyUser()) {
                            $id = $payload->userId;
                            $role = $_POST['role'];

                            // query to update the new password...
                            $update = $wpdb->update('staff',
                                ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                                    'updated_at' => Date('Y-m-d h:i:s')],
                                ['forgot_password_token' => $token, 'id' => $id, 'role' => $role]);
                        }
                        break;
                }
                break;

        }

        // if password updated successfully...
        if ($update) {
            $response = ['status' => Success_Code, 'message' => 'Password Updated Successfully'];
        }
        // if password not updated...
        else {
            throw new Exception('Password not updated');
        }

        // catch the exceptions...
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }

    // if user directly access this page...
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}

echo json_encode($response);
