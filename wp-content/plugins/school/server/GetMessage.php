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

                    $sql = "SELECT m.id as m_id, m.from_user, m.to_user, m.status,m.message,m.subject,
                    m.created_at,u.f_name,u.id,u.l_name FROM messages m INNER JOIN users as u on
                    m.from_user=u.id 
                    WHERE `to_user` =$id OR `from_user`=$id ORDER BY m.created_at DESC LIMIT 0,1";

                    // echo $sql;die;
                    $messages = $wpdb->get_results($sql);
                    $response = ['status' => Success_Code, 'message' => "Messages Fetched Successfully",
                        'messages' => $messages];

                    break;

                case 'getAllMessages':

                    $sql = "SELECT * from messages where to_user=$id or from_user=$id";

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
