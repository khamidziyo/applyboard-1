<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . "/common/autoload.php")) {
    include_once dirname(__FILE__, 3) . "/common/autoload.php";
}

if (!empty($_POST)) {

    try {

        // value wheter a google login or facebook login...
        switch ($_POST['val']) {

            // if user logins with facebook...
            case 'facebookLogin':
                unset($_POST['val']);

                // to check whether user exists or not...
                $sql = "select * from users where social_id=" . $_POST['social_id'];

                $user = $wpdb->get_results($sql);

                // if user exists...
                if (!empty($user)) {

                    if ($user[0]->role != "1") {
                        throw new Exception('Unauthorized Access for user ' . $user[0]->email);
                    }

                    // function that checks the user status...
                    checkUserStatus($user);

                    // if user does not exists...
                } else {
                    $_POST['created_at'] = date('Y-m-d h:i:s');
                    $_POST['status'] = "1";

                    // inserting the user in database...
                    $user = $wpdb->insert('users', $_POST);

                    // returning success response...
                    if ($user) {
                        $payload = [
                            'iat' => time(),
                            'iss' => 'localhost',
                            'exp' => time() + (60),
                            'userId' => $wpdb->insert_id,
                        ];

                        $access_token = Jwt::encode($payload, Secret_Key);

                        $data = ['email' => $_POST['email'], 'token' => $access_token];

                        $response = ['status' => 200, 'message' => "Login Success", 'data' => $data];

                        // returning error response if found any error...
                    } else {
                        throw new Exception("Unable to sign in due to server error");
                    }
                }
                break;

            // if user logins with google...
            case 'googleLogin':
                unset($_POST['val']);

                // to check whether user exists or not...
                $sql = "select * from users where social_id=" . $_POST['social_id'];

                $user = $wpdb->get_results($sql);

                // if user exists...
                if (!empty($user)) {

                    if ($user[0]->role != "1") {
                        throw new Exception('Unauthorized Access for user ' . $user[0]->email);
                    }

                    // function that checks the user status...
                    checkUserStatus($user);

                    // if user does not exists...
                } else {
                    $_POST['created_at'] = date('Y-m-d h:i:s');
                    $_POST['status'] = "1";

                    // inserting the user in database...
                    $res = $wpdb->insert('users', $_POST);

                    // returning success response...
                    if ($res) {
                        $payload = [
                            'iat' => time(),
                            'iss' => 'localhost',
                            'exp' => time() + (60),
                            'userId' => $wpdb->insert_id,
                        ];

                        $access_token = Jwt::encode($payload, Secret_Key);

                        $data = ['id' => base64_encode($wpdb->insert_id), 'email' => $_POST['email'], 'token' => $access_token];

                        $response = ['status' => 200, 'message' => "Login Success", 'data' => $data];

                        // returning error response if found any error...
                    } else {
                        throw new Exception("Unable to sign in due to server error");
                    }
                }
                break;

            case 'normalLogin':
                $email = $_POST['email'];
                $password = $_POST['password'];

                // if a mail is invalid...
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Invalid email');
                }

                // query that fetch the data of user where email exists...
                $sql = "select * from users where email='" . $email . "'";
                $user = $wpdb->get_results($sql);

                // if user with that email exists...
                if (!empty($user)) {

                    $pass = $user[0]->password;

                    // then verify its password...
                    if (password_verify($password, $pass)) {

                        if ($user[0]->role != "1") {
                            throw new Exception('Unauthorized Access for user ' . $email);
                        }

                        // function that checks the user status...
                        checkUserStatus($user);

                    }

                    //if password not exists...
                    else {
                        throw new Exception('Password not exists');
                    }
                }

                // if the mail does not exists...
                else {
                    throw new Exception('This email does not exist');
                }
                break;

            // if no case natches...
            default:
                throw new Exception("No match Found");
                break;
        }
    }
    // returning error exception if any error is thrown...
     catch (Exception $e) {
        $response = ['status' => 400, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => 400, 'message' => "Unauthorized Access"];
}

function checkUserStatus($user)
{
    switch ($user[0]->status) {

        // if user not verified...
        case 0:
            $response = [
                'status' => Not_Verified,
                'message' => "Your account is not verified.Please verify",
                'data' => ['id' => $user[0]->id, 'email' => $user[0]->email]];
            break;

        // if user is an active user...
        case 1:
            $payload = [
                'iat' => time(),
                'iss' => 'localhost',
                'exp' => time() + (24 * 60 * 60),
                'userId' => $user[0]->id,
                'role' => $user[0]->role,
            ];

            $access_token = Jwt::encode($payload, Secret_Key);
            // echo $access_token;die;
            $data = ['id' => base64_encode($user[0]->id), 'role' => $user[0]->role, 'email' => $user[0]->email, 'token' => $access_token];

            $response = ['status' => Success_Code, 'message' => "Login Success", 'data' => $data];
            break;

        // if user account is deactivated...
        case 2:
            $response = ['status' => Error_Code, 'message' => "Your account is deactivated."];
            break;
    }

// return the json response...
    echo json_encode($response);
    exit();
}

// return the json response...
echo json_encode($response);
