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

if (!empty($_POST['val'])) {

    try {

        $require_arr = ['password', 'confirm_password', 'token', 'role'];

        foreach ($require_arr as $form_input) {
            if (empty($form_input)) {
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
                        if (adminVerifyUser()) {
                            // query to update the new password...
                            $update = $wpdb->update('users', ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)],
                                ['forgot_password_token' => $token, 'id' => $student_id]);
                        }
                        break;

                    case '2':
                        break;

                    case '3':
                        $role = $_POST['role'];
                        if (agentVerifyUser()) {
                            $id = $payload->userId;
                          
                            // query to update the new password...
                            $update = $wpdb->update('agents', [
                                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                                'updated_at' => Date('Y-m-d h:i:s'),
                            ],
                                ['forgot_password_token' => $token, 'id' => $id, 'role' => $role,
                                ]);
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
