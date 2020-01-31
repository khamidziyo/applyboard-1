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

if (!empty($_GET['val'])) {
    try {
        if (verifyUser()) {
      

            $id = $payload->userId;

            switch ($_GET['val']) {

                // to get all the messages...
                case 'getMessages':
                    $sent_msg_sql = "SELECT m.*,u.id as u_id, CONCAT_WS(' ',u.f_name,u.l_name) as name FROM messages as m INNER JOIN users
                     AS u ON u.id=m.to_user   WHERE m.id IN(SELECT MAX(m.id) FROM messages as m
                      WHERE from_user=$id GROUP BY to_user)";

                    $sent_msgs = $wpdb->get_results($sent_msg_sql);

                    //     $sent_msg_sql = "SELECT m.*,u.id as u_id, CONCAT_WS(' ',u.f_name,u.l_name) as name FROM messages as m INNER JOIN users
                    //     AS u ON u.id=m.from_user   WHERE m.id IN(SELECT MAX(m.id) FROM messages as m
                    //      WHERE to_user=$id GROUP BY from_user)";

                    //    $messages = $wpdb->get_results($sql);

                    $response = ['status' => Success_Code, 'message' => "Messages Fetched Successfully",
                        'sent_messages' => $sent_msgs];

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
