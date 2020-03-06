<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../wp-config.php';
}

if (file_exists('autoload.php')) {
    include_once 'autoload.php';
}

function staffVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Staff::verifyUser($payload);
}

function studentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Student::verifyUser($payload);
}

function agentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Agent::verifyUser($payload);
}

if (!empty($_GET['val'])) {
    try {
        switch ($_GET['val']) {

            case 'getMessages':

                // if messsage body is empty...
                if (empty($_GET['user'])) {
                    throw new Exception("User id is required");
                }

                if (empty($_GET['role'])) {
                    throw new Exception("User role is required");
                }

                $user_id = $_GET['user'];

                $role = $_GET['role'];

                switch ($_GET['role']) {

                    // if logged in user is student...
                    case '1':
                        break;

                    // if the logged in user is agent and want to view message of particular student...
                    case '3':
                        if (agentVerifyUser()) {

                            $sql = "SELECT * from messages where receiver_id=$user_id or sender_id=$user_id";

                            $messages = $wpdb->get_results($sql);

                            $response = ['status' => Success_Code, 'message' => 'Message fetched successfully',
                                'messages' => $messages];
                        }
                        break;

                    // if logged in user is staff member...
                    case '5':
                        if (staffVerifyUser()) {
                            $id = $payload->userId;

                            $sql = "SELECT * from messages where receiver_id=$id && sender_id=$user_id or
                            sender_id=$id && receiver_id=" . $user_id . " && role in ('1','5')";

                            $messages = $wpdb->get_results($sql);
                            $response = ['status' => Success_Code, 'message' => 'Message fetched successfully',
                                'messages' => $messages, 'logged_user' => $id];
                        }
                        break;
                }

                break;

            default:
                throw new Exception("No case matches.Unauthorized Access");
                break;
        }
    } catch (Exception $e) {
        $wpdb->query('ROLLBACK');

        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

echo json_encode($response);
