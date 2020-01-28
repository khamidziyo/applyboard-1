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
            $id=$payload->userId;

            switch ($_GET['val']) {
                
                case 'getNotifications':

                    // notification sql to get all the notification of school...
                    $notification_sql = "select u.email as u_email,c.name as c_name,n.* from notifications as n
                         join users as u on n.from_user=u.id join courses as c on c.id=n.course_id
                         where to_user=" . $id;

                    $notification = $wpdb->get_results($notification_sql);

                    if (!empty($notification)) {
                        $response = ['status' => Success_Code, 'message' => 'School notifications fetched successfully',
                            'notification' => $notification, 'notification_count' => count($notification)];
                    } else {
                        throw new Exception('No notification found');
                    }
                    break;

                default:
                    throw new Exception("No match found");
                    break;

            }
        }

    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is Required"];
}

//returning json response...
echo json_encode($response);
