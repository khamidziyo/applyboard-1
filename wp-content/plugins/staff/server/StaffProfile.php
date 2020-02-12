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
    return Staff::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    if (verifyUser()) {
        try {

            switch ($_POST['val']) {
                case 'getStaffProfile':

                    $id = $payload->userId;
                    $sql = 'select id,email,image,status from staff where id=' . $id;
                    $data = $wpdb->get_results($sql);

                    if (!empty($data)) {
                        $response = ['status' => Success_Code, 'message' => 'Staff profile fetched successfully', 'data' => $data[0]];
                    } else {
                        throw new Exception('No staff found');
                    }
                    break;

                default:
                    throw new Exception("No case matches");
                    break;
            }

        } catch (Exception $e) {
            $response = ['status' => Error_Code, 'message' => $e->getMessage()];
        }
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

echo json_encode($response);
