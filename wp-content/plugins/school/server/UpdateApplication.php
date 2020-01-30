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
    try {
        if (verifyUser()) {
            switch ($_POST['val']) {

                case 'updateStatus':

                    // echo "<pre>";
                    // echo trim($_POST['status']);
                    // print_r($_POST);

                    // die;

                    if (empty($_POST['id'])) {
                        throw new Exception("Application id is required");
                    }

                    if (!isset($_POST['status'])) {
                        throw new Exception("Application status is required");
                    }

                    $app_id = base64_decode($_POST['id']);
  
                    $update = $wpdb->update('applications', ['status' =>$_POST['status']], ['id' => $app_id]);
                    if ($update) {
                        $response=['status'=>Success_Code,'message'=>"Application Status Updated Successfully"];
                    } else {
                        throw new Exception("Error Processing Request", 1);
                    }

                    break;

                default:
                    throw new Exception("No match Found");
                    break;
            }

        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is required"];
}
echo json_encode($response);
