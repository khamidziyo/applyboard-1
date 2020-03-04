<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (!empty($_POST)) {
    $response = [];

    try {
        $require_arr = ['password', 'confirm_password', 'token', 'case', 'user'];

        foreach ($require_arr as $form_input) {

            if (!array_key_exists($form_input, $_POST)) {
                throw new Exception($form_input . " is missing in the request");
            }

            if (empty($_POST[$form_input])) {
                throw new Exception("Please enter the " . $form_input);
            }
        }

        if (strlen($_POST['password']) < 6) {
            throw new Exception("Password should not be less than six characters");
        }

        if ($_POST['password'] != $_POST['confirm_password']) {
            throw new Exception("Password and confirm password are not same");
        }

        // get the forgot password token...
        $token = $_POST['token'];

        // get the user id whose password is to be updated in users table...
        $user = base64_decode($_POST['user']);

        switch ($_POST['case']) {

            case 'student':

                // query to update the new password...
                $update = $wpdb->update('users', ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)],
                    ['forgot_password_token' => $token, 'id' => $user, 'role' => '1']);
                break;

            case 'admin':
                // query to update the new password...
                $update = $wpdb->update('users', ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)],
                    ['forgot_password_token' => $token, 'id' => $user, 'role' => '2']);
                break;

            case 'agent':

                // query to update the new password...
                $update = $wpdb->update('agents', ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)],
                    ['forgot_password_token' => $token, 'id' => $user, 'role' => '3']);
                break;

            case 'subagent':

                // query to update the new password...
                $update = $wpdb->update('agents', ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)],
                    ['forgot_password_token' => $token, 'id' => $user, 'role' => '4']);
                break;

            case 'staff':

                // query to update the new password...
                $update = $wpdb->update('staff', ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)],
                    ['forgot_password_token' => $token, 'id' => $user, 'role' => '5']);
                break;

            default:
                $response = ['status' => Error_Code, 'message' => 'No case matches.Try again'];
                break;

        }

        // if password updated successfully...
        if ($update) {
            $response = ['status' => Success_Code, 'message' => "Password Updated Successfully"];
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
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access"];
}

echo json_encode($response);
