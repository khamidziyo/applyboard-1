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

if (!empty($_POST['val'])) {
    try {
        switch ($_POST['val']) {
            case 'sendMessage':

                // if messsage body is empty...
                if (empty($_POST['message'])) {
                    throw new Exception("Please enter the message");
                }

                if (empty($_POST['receiver_id'])) {
                    throw new Exception("Receiver id is required");
                }

                if (empty($_POST['role'])) {
                    throw new Exception("User role is missing in the request");
                }

                $role = $_POST['role'];

                switch ($role) {

                    // if logged in user is student...
                    case '1':
                        $path = dirname(__DIR__) . '/student/assets/documents/';

                        if (studentVerifyUser($path)) {

                        }
                        break;

                    // if logged in user is agent...
                    case '3':
                        if (agentVerifyUser()) {
                            $id = $payload->userId;
                            $sender_id = $_POST['sender_id'];

                            sendMessage($wpdb, $sender_id, $path);

                        }
                        break;

                    // if logged in user is staff member...
                    case '5':
                        $path = dirname(__DIR__) . '/staff/assets/documents/';

                        if (staffVerifyUser()) {
                            $sender_id = $payload->userId;
                            sendMessage($wpdb, $sender_id, $path);
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

function sendMessage($wpdb, $sender_id, $path)
{
    $allowedFileTypes = ['jpg', 'jpeg', 'png', 'pdf', 'xlsx'];
    $doc_arr = [];

    $wpdb->query('START TRANSACTION');

    $message = trim($_POST['message']);

    $receiver_id = $_POST['receiver_id'];

    if (!empty($_FILES['file_input']['name'][0])) {

        foreach ($_FILES['file_input']['name'] as $key => $file_name) {
            $file_type = pathinfo($file_name, PATHINFO_EXTENSION);

            if (!in_array($file_type, $allowedFileTypes)) {
                throw new Exception("Documents of only jpg,jpeg,png,pdf and excel formats are allowed");
            }

            if ($_FILES['file_input']['size'][$key] > 2 * 1024 * 1024) {
                throw new Exception("Document size should not exceed more than 2 MB.");
            }

            $doc_name = microtime() . '_' . $sender_id . '.' . $file_type;

            $doc_path = $path . $doc_name;

            if (!move_uploaded_file($_FILES['file_input']['tmp_name'][$key], $doc_path)) {
                throw new Exception("Document could not be uploaded due to internal server error");
            }

            $doc_arr[] = $doc_name;

            $insert_arr = ['user_id' => $sender_id, 'document' => $doc_name, 'role' => $_POST['role'], 'created_at' => Date('Y-m-d h:i:s')];
            $insert_doc_res = $wpdb->insert('user_documents', $insert_arr);

            if (!$insert_doc_res) {
                throw new Exception("Document could not be inserted due to internal server error");
            }
        }
    }

    $insert_msg_arr = ['sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'message' => $message,
        'document' => json_encode($doc_arr), 'role' => $_POST['role'], 'created_at' => Date('Y-m-d h:i:s')];

    $msg_insert_res = $wpdb->insert('messages', $insert_msg_arr);

    if (!$msg_insert_res) {
        throw new Exception("Message could not be sent due to internal server error");
    }

    $wpdb->query('COMMIT');

    $response = ['status' => Success_Code, 'message' => 'Message sent successfully'];

    echo json_encode($response);
    exit;
}
echo json_encode($response);
