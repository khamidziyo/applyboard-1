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
    return Admin::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {
        if (verifyUser()) {
            switch ($_POST['val']) {

                case 'addStaff':
                    $allowedTypes = ['jpg', 'jpeg', 'png'];
                    $img_name;

                    $id = $payload->userId;

                    $require_arr = ['name', 'email', 'password', 'confirm_password'];

                    foreach ($require_arr as $form_input) {
                        if (!array_key_exists($form_input, $_POST)) {
                            throw new Exception($form_input . " is missing in the request");
                        } elseif (empty($_POST[$form_input])) {
                            throw new Exception("Please enter the " . $form_input);
                        }
                    }
                    $email = $_POST['email'];

                    if (!filter_var($email, FILTER_DEFAULT)) {
                        throw new Exception("Invalid email address");
                    }

                    $email_exist = $wpdb->get_results("select email from staff where email='" . $email . "'");

                    if (!empty($email_exist)) {
                        throw new Exception("This email address already exist.Try another.");
                    }
                    $password = $_POST['password'];

                    if (strlen($password) < 6) {
                        throw new Exception("Password length should be minimum six characters");
                    }

                    if ($password != $_POST['confirm_password']) {
                        throw new Exception("Password and confirm password should be same");
                    }

                    $password = password_hash($password, PASSWORD_DEFAULT);

                    if (!empty($_FILES['img_input']['name'])) {
                        $img_name = $_FILES['img_input']['name'];

                        $img_type = pathinfo($img_name, PATHINFO_EXTENSION);

                        if (!in_array($img_type, $allowedTypes)) {
                            throw new Exception("Only jpg,jpeg and png formats are allowed");
                        }

                        if ($_FILES['img_input']['size'] > 2 * 1024 * 1024) {
                            throw new Exception("Image size should not exceed more than 2 MB");
                        }

                        $path = dirname(__DIR__, 2) . "/staff/assets/images/";
                        $img_name = microtime() . '.' . $img_type;

                        if (!move_uploaded_file($_FILES['img_input']['tmp_name'], $path . $img_name)) {
                            throw new Exception("Image could not be uploaded on server");
                        }
                    }

                    $insert_arr = ['user_id' => $id, 'name' => $_POST['name'], 'email' => $email,
                        'password' => $password, 'image' => $img_name, 'created_at' => Date('Y-m-d h:i:s')];

                    $staff_ins_res = $wpdb->insert('staff', $insert_arr);

                    if ($staff_ins_res) {
                        $response = ['status' => Success_Code, 'message' => 'Staff Created Successfully'];

                    } else {
                        throw new Exception("Staff could not be created due to internal server error");
                    }
                    break;

                default:
                    throw new Exception("No case matches");
                    break;
            }

        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

echo json_encode($response);
