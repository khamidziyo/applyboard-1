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

            case 'getNotification':

                if (empty($_GET['role'])) {
                    throw new Exception("User role is required");
                }
                $role = $_GET['role'];

                switch ($role) {

                    // if logged in user is agent...
                    case '3':
                        if (agentVerifyUser()) {
                            $id = $payload->userId;
                            // echo $id;
                            // die;
                            $student_sql = "select id,agent_id from users where agent_id=$id";
                            $students = $wpdb->get_results($student_sql);

                            // if students has been created by agents...
                            if (!empty($students[0])) {
                                foreach ($students as $key => $obj) {
                                    $ids[] = $obj->id;
                                }
                                $where = " && role='1'";
                            }
                            $where .= " && '3'";
                            $ids[] = $id;

                            $notification_sql = "select n.*,a.status as app_status,c.id,c.name from notifications as n left join courses as c on c.id=n.course_id left join applications as a on a.id=n.application_id
                              where to_user in (" . implode(",", $ids) . ")" . $where;

                            //   echo $notification_sql;
                            //   die;
                            $notifications = $wpdb->get_results($notification_sql);

                            $response = ['status' => Success_Code, 'message' => 'Notification fetched successfully',
                                'notification' => $notifications];
                        }
                    break;

                    // if logged in user is staff...
                    case '5':
                        if (staffVerifyUser()) {
                            $id = $payload->userId;
                            $school_sql = "select id from school where staff='1'";
                            $schools = $wpdb->get_results($school_sql);

                            if (!empty($schools[0])) {
                                foreach ($schools as $key => $obj) {
                                    $school_ids[] = $obj->id;
                                }
                            }

                            $notification_sql = "select n.*,n.id as n_id,a.status as app_status,c.id,c.name from
                            notifications as n left join courses as c on c.id=n.course_id left join
                            applications as a on a.id=n.application_id
                              where to_user in (" . implode(",", $school_ids) . ")";

                            $notifications = $wpdb->get_results($notification_sql);
                            if (!empty($notifications)) {
                                foreach ($notifications as $key => $obj) {
                                    $notification_data;
                                    switch ($obj->role) {

                                        // if the notification came from agents...
                                        case '3':

                                            // query in agent table...
                                            $agent = $wpdb->get_results("select name as agent_name,image
                                             as agent_image from agents where id=" . $obj->from_user);
                                            break;
                                    }

                                    $notification_data[] = ['id' => $obj->n_id, 'course_name' => $obj->name, 'message' => $obj->message,
                                        'agent_name' => $agent[0]->agent_name, 'app_status' => $obj->app_status,
                                        'created_at' => $obj->created_at, 'role' => $obj->role, 'agent_image' => $agent[0]->agent_image];
                                }
                            }
                            $response = ['status' => Success_Code, 'message' => 'Notification fetched successfully',
                                'notification' => $notification_data];
                        }
                        break;

                        break;

                }
                break;
        }

    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

echo json_encode($response);
