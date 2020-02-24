<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function agentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Agent::verifyUser($payload);
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

            case 'validateOldPassword':
                if (agentVerifyUser()) {

                    $id = $payload->userId;

                    validateOldPassword($wpdb, $id);

                }
                break;

            case 'validateOldPasswordByAdmin':
                if (adminVerifyUser()) {

                    if (empty($_POST['agent_id'])) {
                        throw new Exception("Agent id is required");
                    }

                    $id = base64_decode($_POST['agent_id']);
  
                    validateOldPassword($wpdb, $id);

                }
                break;

            case 'updateProfileByAgent':
                if (agentVerifyUser()) {
                    $id = $payload->userId;
                    updateAgentProfile($wpdb, $id);
                }

            case 'updateProfileByAdmin':
                if (adminVerifyUser()) {

                    if (empty($_POST['agent_id'])) {
                        throw new Exception("Agent id is required");
                    }
                    $agent_id = base64_decode($_POST['agent_id']);

                    updateAgentProfile($wpdb, $agent_id);
                }
                break;

            default:
                throw new Exception("No match Found.");
                break;
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

function updateAgentProfile($wpdb, $id)
{
    $allowedTypes = ['jpg', 'jpeg', 'png'];

    $update_arr = [];

    $img_name = $_POST['bus_image'];

    if (!empty($_FILES['business_img']['name'])) {

        $img_name = $_FILES['business_img']['name'];
        $img_type = pathinfo($img_name, PATHINFO_EXTENSION);

        if (!in_array($img_type, $allowedTypes)) {
            throw new Exception("Only jpg,jpeg and png formats are allowed");
        }

        if ($_FILES['size'] > 2 * 1024 * 1024) {
            throw new Exception("Image size should not exceed more than 2 MB");
        }

        $img_name = microtime() . '_' . $id . '.' . $img_type;
        $path = dirname(__DIR__) . '/assets/images/';

        if (!move_uploaded_file($_FILES['business_img']['tmp_name'], $path . $img_name)) {
            throw new Exception("Image not uploaded on sever directory.Try again");
        }

        if (!unlink($path . $_POST['bus_image'])) {
            throw new Exception("previous Image could not be deleted due to internal server error.Try again");
        }
    }

    $update_arr = ['name' => $_POST['person_name'], 'email' => $_POST['person_mail'],
        'contact_number' => $_POST['person_number'], 'address' => $_POST['person_address'],
        'business_name' => $_POST['business_name'], 'business_email' => $_POST['business_email'],
        'business_phone' => $_POST['business_phone'], 'business_address' => $_POST['business_address'],
        'business_website' => $_POST['business_site'], 'image' => $img_name, 'updated_at' => Date('Y-m-d h:i:s')];

    $update_profile = $wpdb->update('agents', $update_arr, ['id' => $id]);

    // if profile updated successfully...
    if ($update_profile) {
        $response = ['status' => Success_Code, 'message' => 'Profile Updated Successfully'];
    } else {
        throw new Exception("Profile not updated due to internal server error");
    }

    echo json_encode($response);
    exit;

}

function validateOldPassword($wpdb, $id)
{
    // to get the old password...
    $old_password = $_POST['password'];

    //query to get the current password...
    $sql = 'select password from agents where id=' . $id;
    $data = $wpdb->get_results($sql);

    $current_password = $data[0]->password;

    // if password does not matches with the current password...
    if (!password_verify($old_password, $current_password)) {
        throw new Exception('Password is incorrect');
    }
    // if password is correct...
    else {
        // creating  a token...
        $token = md5(rand(10000, 100000000000));

        // update token in users table...
        $wpdb->update('agents', ['forgot_password_token' => $token], ['id' => $id, 'role' => '3']);
        $data = ['token' => $token];

        // returning the response...
        $response = ['status' => Success_Code, 'message' => 'Password is correct', 'data' => $data];
    }

    echo json_encode($response);
    exit;
}

echo json_encode($response);
