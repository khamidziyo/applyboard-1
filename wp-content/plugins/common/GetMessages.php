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
        if (empty($_GET['role'])) {
            throw new Exception("User role is required");
        }

        switch ($_GET['val']) {

            case 'getMessages':

                $role = $_GET['role'];

                switch ($role) {

                    // if logged in user is student...
                    case '1':
                        break;

                    // if the logged in user is agent and want to view message of particular student...
                    case '3':
                        if (agentVerifyUser()) {
                            $user_id = $payload->userId;

                            $students = $wpdb->get_results("select id from users where agent_id=$user_id && role='1'");

                            foreach ($students as $key => $obj) {
                                $students_ids[] = $obj->id;
                            }

                            $ids = implode(",", $students_ids);

                            $sql = "select m.*,staff.name from messages as m left join staff on staff.id =m.sender_id join (select user, max(created_at) created from ((select id, receiver_id user, created_at
                   from messages as m where sender_id in ($ids) ) union (select id, sender_id user, created_at from messages as m where receiver_id in ($ids))
                ) t1 group by user) t2 on ((sender_id in ($ids) and receiver_id=user) or (sender_id=user and receiver_id in ($ids))) and
                    (m.created_at = created) order by m.created_at DESC";

                            $messages = $wpdb->get_results($sql);

                            $response = ['status' => Success_Code, 'message' => 'Message fetched successfully',
                                'messages' => $messages, 'logged_user' => $user_id];
                        }
                        break;

                    // if logged in user is staff member...
                    case '5':
                        if (staffVerifyUser()) {
                            $id = $payload->userId;

                            // student id to whom the message will be delivered...

                            if (empty($_GET['user'])) {
                                throw new Exception("Receiver id is required");
                            }

                            $user_id = $_GET['user'];

                            $sql = "SELECT * from messages where receiver_id=$id && sender_id=$user_id or
                            sender_id=$id && receiver_id=" . $user_id . " && role in ('1','5')";

                            $messages = $wpdb->get_results($sql);
                            $response = ['status' => Success_Code, 'message' => 'Message fetched successfully',
                                'messages' => $messages, 'logged_user' => $id];
                        }
                        break;
                }

                break;

            case 'getAllMessages':
                $role = $_GET['role'];

                switch ($role) {

                    // if logged in user is student...
                    case '1':
                        break;

                    // if the logged in user is agent and want to view message of particular student...
                    case '3':

                        if (agentVerifyUser()) {

                            $user_id = $payload->userId;

                            if (empty($_GET['sender_id'])) {
                                throw new Exception("Sender id is missing in the request");
                            }

                            if (empty($_GET['receiver_id'])) {
                                throw new Exception("Receiver id is missing in the request");
                            }

                            $sender_id = $_GET['sender_id'];
                            $receiver_id = $_GET['receiver_id'];

                            $message_sql = "select * from messages where sender_id=$sender_id && receiver_id=$receiver_id
                             or sender_id=$receiver_id && receiver_id=$sender_id";

                            $messages = $wpdb->get_results($message_sql);

                            $response = ['status' => Success_Code, 'message' => 'All messages fetched successfully',
                                'messages' => $messages, 'logged_user' => $user_id];
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
