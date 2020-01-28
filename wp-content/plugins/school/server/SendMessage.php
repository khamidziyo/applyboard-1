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
    return School::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {
        if (verifyUser()) {

            // if the message subject is empty...
            if (empty($_POST['subject'])) {
                throw new Exception("Please enter the message subject");
            }

            // if the message is empty...
            if (empty($_POST['message'])) {
                throw new Exception("Please enter the message");
            }

            // if the user id is empty...
            if (empty($_POST['id'])) {
                throw new Exception("User is required");
            }

            // decode the user id...
            $user = base64_decode($_POST['id']);

            // message array that is to be inserted...
            $insert_msg_arr = ['to_user' => $user, 'subject' => $_POST['subject'],
                'message' => trim($_POST['message']), 'from_user' => $payload->userId, 'created_at' => date('Y-m-d h:i:s')];

                // query to insert the message...
            $insert_msg_res = $wpdb->insert('messages', $insert_msg_arr);

            // if message inserted successfully....
            if ($insert_msg_res) {
                $response = ['status' => Success_Code, 'message' => "Message Sent Successfully"];
            }
            // if the message not inserted...
            else {
                throw new Exception("Internal server error while sending your message");
            }
        }
    }
    // if any exception occurs...
    catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
}

// if user directly access this page....
else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is required"];
}

// return the json response...
echo json_encode($response);
