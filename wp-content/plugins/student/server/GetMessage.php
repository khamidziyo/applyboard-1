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

if (!empty($_GET['val'])) {
    try {
        if (verifyUser()) {
            $id = $payload->userId;

            switch ($_GET['val']) {

                // to get all the messages...
                case 'getMessages':

                    $sql = "SELECT m.*,s.id as u_id,s.name FROM messages as m INNER JOIN staff
                     AS s ON s.id=m.sender_id   WHERE m.id IN(SELECT MAX(m.id) FROM messages as m
                      WHERE receiver_id=$id GROUP BY sender_id)";

                    $messages = $wpdb->get_results($sql);

                    $response = ['status' => Success_Code, 'message' => "Messages Fetched Successfully",
                        'messages' => $messages];

                    break;

                case 'getAllMessages':

                    // if the recipient or sender id is not in the request..
                    if (empty($_GET['user'])) {
                        throw new Exception("User id is required");
                    }

                    $user_id = base64_decode($_GET['user']);

                    $sql = "SELECT * from messages where to_user=$id and from_user=$user_id or
                    from_user=$id and to_user=" . $user_id;

                    $messages = $wpdb->get_results($sql);

                    $response = ['status' => Success_Code, 'message' => "All Messages Fetched Successfully",
                        'messages' => $messages, 'id' => $id];

                    break;
                // if no case matches...
                default:
                    throw new Exception("No match Found");
                    break;
            }
        }
    }

    // if any exception is thrown...
     catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
}

// if user directly access this page...
else {
    $response = ['status' => Error_Code, 'message' => "Value is Required,Unauthorized Access"];
}

//returning json response...
echo json_encode($response);
