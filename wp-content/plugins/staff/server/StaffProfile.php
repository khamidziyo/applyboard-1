<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function staffVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Staff::verifyUser($payload);
}

function adminVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {

        switch ($_POST['val']) {
            case 'getStaffProfile':

                if (staffVerifyUser()) {
                    $id = $payload->userId;

                    getStaffProfile($wpdb, $id);
                }
                break;

            case 'getStaffProfileByAdmin':
                if (adminVerifyUser()) {

                    if (empty($_POST['staff'])) {
                        throw new Exception("Staff id is required");
                    }
                    $staff_id = base64_decode($_POST['staff']);

                    getStaffProfile($wpdb, $staff_id);
                }
                break;

            default:
                throw new Exception("No case matches");
                break;
        }

    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

function getStaffProfile($wpdb, $staff_id)
{
    $sql = 'select id,name,email,image,status from staff where id=' . $staff_id;
    $data = $wpdb->get_results($sql);

    if (!empty($data)) {
        $response = ['status' => Success_Code, 'message' => 'Staff profile fetched successfully', 'data' => $data[0]];
    } else {
        throw new Exception('No staff found');
    }
    echo json_encode($response);
    exit;
}
echo json_encode($response);
