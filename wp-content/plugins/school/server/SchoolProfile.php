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
    if (verifyUser()) {

        try {
            $response = [];

            // switch case to match the user profile...
            switch ($_POST['val']) {

                // user profile cases...
                case 'schoolProfile':
                    $id = $payload->userId;

                    $sql = 'select id,email,profile_image as image,status from school where id=' . $id;
                    $data = $wpdb->get_results($sql);

                    $notification_count=$wpdb->get_results("select count(id) as total_count from notifications where to_user=".$id." && status='0'");

                    // notification sql to get all the notification of school...
                    $notification_sql = "select u.email as u_email,c.name as c_name,n.* from notifications as n
                            join users as u on n.from_user=u.id join courses as c on c.id=n.course_id
                            where to_user=" . $id . " && n.status='0' order by n.id desc limit 0,2";

                    $notification = $wpdb->get_results($notification_sql);


                    if (!empty($data)) {
                        $response = ['status' => Success_Code, 'message' => 'School profile fetched successfully',
                        'notification'=>$notification,'data' => $data[0],'notification_count'=>$notification_count[0]->total_count];

                    } else {
                        throw new Exception('Invalid User');
                    }
                    break;

                // if no case matches...
                default:
                    throw new Exception('No match found');
                    break;
            }
        } catch (Exception $e) {
            $response = ['status' => Error_Code, 'message' => $e->getMessage()];
        }
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}
echo json_encode($response);
