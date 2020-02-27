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

                case 'validateOldPassword':
                    $id = $payload->userId;

                    // to get the old password...
                    $old_password = $_POST['password'];

                    //query to get the current password...
                    $sql = 'select password from staff where id=' . $id;
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
                        $wpdb->update('staff', ['forgot_password_token' => $token], ['id' => $id]);
                        $data = ['token' => $token];

                        // returning the response...
                        $response = ['status' => Success_Code, 'message' => 'Password is correct', 'data' => $data];
                    }

                    break;

                // update the profile...
                case 'updateProfile':
                    $id = $payload->userId;

                    $require_arr = ['name', 'email'];

                    foreach ($require_arr as $form_input) {
                        if (!array_key_exists($form_input, $_POST)) {
                            throw new Exception("Please enter " . $form_input);
                        }
                    }

                    $email = $_POST['email'];

                    // if email is invalid...
                    if (!filter_var($email, FILTER_DEFAULT)) {
                        throw new Exception("Invalid email address");
                    }

                    $email_exist = $wpdb->get_results("select email from staff where email='" . $email . "' && id!=$id");

                    // if new email already exist...
                    if (!empty($email_exist)) {
                        throw new Exception("This email already exist.Try another.");
                    }

                    $img_name = $_POST['cur_image'];

                    if (!empty($_FILES['img_input']['name'])) {

                        // images of type which are allowed...
                        $allowedType = ['jpg', 'jpeg', 'png'];

                        $img_name = $_FILES['img_input']['name'];

                        // get the image type...
                        $img_type = pathinfo($img_name, PATHINFO_EXTENSION);

                        // if image type does not matches...
                        if (!in_array($img_type, $allowedType)) {
                            throw new Exception("Only jpg,jpeg and png formats are allowed");
                        }

                        // if image size exceeds more than 2 MB...
                        if ($_FILES['img_input']['size'] > 2 * 1024 * 1024) {
                            throw new Exception("Image size should not exceed more than 2 MB");
                        }

                        // creating a new image name...
                        $img_name = microtime() . "." . $img_type;

                        $path = dirname(__DIR__, 1) . "/assets/images/";

                        // move new image file to folder...
                        if (!move_uploaded_file($_FILES['img_input']['tmp_name'], $path . $img_name)) {
                            throw new Exception("Image could not be uploaded to server directory due to internal server error");
                        }

                        // deleting the previous image...
                        if (!unlink($path . $_POST['cur_image'])) {
                            throw new Exception("Previous Image could not be deleted from server due to internal server error");
                        }
                    }

                    // update array to update the staff...
                    $update_arr = ['name' => $_POST['name'], 'email' => $email, 'image' => $img_name,
                        'updated_at' => Date('Y-m-d h:i:s')];

                    $update_profile_res = $wpdb->update('staff', $update_arr, ['id' => $id]);

                    // if update success...
                    if ($update_profile_res) {
                        $response = ['status' => Success_Code, 'message' => 'Profile Updated Successfully'];
                    }

                    // if update is not success...
                    else {
                        throw new Exception("Profile not updated due to internal server error");
                    }

                    break;

                // if no case matches...
                default:
                    throw new Exception("No case found");
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
