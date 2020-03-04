<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function subAgentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return SubAgent::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {
        if (subAgentVerifyUser()) {
            switch ($_POST['val']) {

                case 'validateOldPassword':

                    $id = $payload->userId;

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
                        $wpdb->update('agents', ['forgot_password_token' => $token], ['id' => $id, 'role' => '4']);
                        $data = ['token' => $token];

                        // returning the response...
                        $response = ['status' => Success_Code, 'message' => 'Password is correct', 'data' => $data];
                    }
                    break;

                case 'updateSubAgentProfile':
                    $id = $payload->userId;
                    $allowedTypes = ['jpg', 'jpeg', 'png'];

                    $require_arr = ['name', 'email', 'number'];
                    foreach ($require_arr as $form_input) {
                        if (!array_key_exists($form_input, $_POST)) {
                            throw new Exception("Please enter your " . $form_input);
                        }
                    }
                    $email = $_POST['email'];
                    $name = $_POST['name'];
                    $number = $_POST['number'];

                    // if the email is invalid...
                    if (!filter_var($email, FILTER_DEFAULT)) {
                        throw new Exception("Invalid email address");
                    }

                    // query to check new email already exists...
                    $email_exist = $wpdb->get_results("select email from agents where email='$email' && id!=" . $id);

                    // if the new email already exists...
                    if (!empty($email_exist[0])) {
                        throw new Exception("This email address already exists");
                    }

                    // if length of the number is greater than 10...
                    if (strlen($number) > 10) {
                        throw new Exception("Mobile number cannot be greater than 10 digits");
                    }

                    // get the previous image name...
                    $img_name = $_POST['cur_image'];

                    if (!empty($_FILES['img_input']['name'])) {

                        $img_name = $_FILES['img_input']['name'];

                        // get the type of the image...
                        $img_type = pathinfo($img_name, PATHINFO_EXTENSION);

                        // if image type does not matches with the type defined...
                        if (!in_array($img_type, $allowedTypes)) {
                            throw new Exception("Only jpg,jpeg and png formats are allowed");
                        }

                        // if image size is greater than 2MB...
                        if ($_FILES['img_input']['size'] > 2 * 1024 * 1024) {
                            throw new Exception("Image size should not exceed more than 2 MB");
                        }

                        $img_name = microtime() . '_' . $id . '.' . $img_type;
                        $path = dirname(__DIR__) . '/assets/images/';

                        // move image to folder...
                        if (!move_uploaded_file($_FILES['img_input']['tmp_name'], $path . $img_name)) {
                            throw new Exception("Image not uploaded on sever directory.Try again");
                        }

                        // delete the previous image...
                        if (!empty($_POST['cur_image'])) {
                            if (!unlink($path . $_POST['cur_image'])) {
                                throw new Exception("previous Image could not be deleted due to internal server error.Try again");
                            }
                        }
                    }

                    $update_arr = ['name' => $name, 'email' => $email, 'contact_number' => $number,
                        'image' => $img_name, 'updated_at' => Date('Y-m-d h:i:s')];

                    // echo "<pre>";
                    // print_r($update_arr);
                    // die;
                    $update_profile = $wpdb->update('agents', $update_arr, ['id' => $id]);

                    // if profile updated successfully...
                    if ($update_profile) {
                        $response = ['status' => Success_Code, 'message' => 'Profile Updated Successfully'];
                    } else {
                        throw new Exception("Profile not updated due to internal server error");
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
