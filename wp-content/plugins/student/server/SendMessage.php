<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

// function to verify user...
function verifyUser()
{
    global $payload;

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {
        if (verifyUser()) {

            // if (!empty($_FILES)) {
            //     echo "<pre>";
            //     print_r($_FILES);
            //     die;
            // }
            $id = $payload->userId;

            // if the message is empty...
            if (empty($_POST['message'])) {
                throw new Exception("Please enter the message");
            }

            // if the user id is empty...
            if (empty($_POST['user'])) {
                throw new Exception("User is required");
            }

            // decode the user id...
            $receiver = base64_decode($_POST['user']);

            // message array that is to be inserted...
            $insert_msg_arr = ['to_user' => $receiver, 'message' => trim($_POST['message']),
                'from_user' => $id, 'created_at' => date('Y-m-d h:i:s a')];

            // transaction starts...
            $wpdb->query('START TRANSACTION');

            // query to insert the message...
            $insert_msg_res = $wpdb->insert('messages', $insert_msg_arr);

            // if message inserted successfully....
            if ($insert_msg_res) {

                if (!empty($_FILES['documents']['name'])) {
                    $document = $_FILES['documents'];

                    foreach ($document['name'] as $key => $name) {

                        // to get the type of document...
                        $doc_type = pathinfo($document['name'][$key], PATHINFO_EXTENSION);

                        // if the type of document does not matches with defined allowed type...
                        if (!in_array($doc_type, $allowedTypes)) {
                            throw new Exception("Only jpg,jpeg and pdf formats allowed");
                        }

                        // if the document size exceeds more than 2 MB...
                        if ($document['size'][$key] > 2 * 1024 * 1024) {
                            throw new Exception("Document size should not exceed more than 2 MB");
                        }

                        // SELECT * FROM messages WHERE id IN(SELECT MAX(id) FROM messages where to_user=37 OR from_user=37 GROUP BY from_user)

                        $path = dirname(__DIR__, 1) . '/assets/documents/';
                        // echo $path;die;

                        $doc_name = microtime() . '_' . $id . '_' . $key . '.' . $doc_type;

                        if (!move_uploaded_file($document['tmp_name'][$key], $path . $doc_name)) {
                            throw new Exception("Document could not be uploaded due to internal server error");
                        }

                        $ins_doc = ['user_id' => $id, 'receiver_id' => $receiver, 'document' => $doc_name, 'created_at' => Date('Y-m-d h:i:s a')];

                        if (!$wpdb->insert('user_documents', $ins_doc)) {
                            throw new Exception("Document could not be inserted due to internal server error");

                        }
                    }
                    // commit transaction here...
                    $wpdb->query('COMMIT');

                } else {

                    // commit transaction here...
                    $wpdb->query('COMMIT');
                }

                pushMessage(trim($_POST['message']));

            }
            // if the message not inserted...
            else {
                throw new Exception("Internal server error while sending your message");
            }
        }
    }
    // if any exception occurs...
     catch (Exception $e) {

        // something went wrong, Rollback
        $wpdb->query('ROLLBACK');

        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
}

// if user directly access this page....
else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is required"];
}

function pushMessage($message)
{
    $options = array(
        'cluster' => 'ap2',
        'useTLS' => true,
    );
    $pusher = new Pusher\Pusher(
        '9d27859f518c27645ae1',
        'e188f03d451b5cfa8179',
        '940473',
        $options
    );

    $data['message'] = 'A new message arrived:</br> ' . $message;
    $pusher->trigger('my-channel', 'my-event', $data);
    $response = ['status' => Success_Code, 'message' => "Message Sent Successfully"];

}
// return the json response...
echo json_encode($response);
