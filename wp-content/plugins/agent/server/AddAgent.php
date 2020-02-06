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

                case 'addAgent':

                    $id = $payload->userId;

                    $allowedExtensions = ['jpg', 'jpeg', 'png'];

                    $require_arr = ['business_name', 'business_email', 'password', 'con_password',
                        'business_address', 'business_phone', 'business_site', 'person_name', 'person_mail',
                        'person_number', 'person_address'];

                    foreach ($require_arr as $key) {
                        if (!array_key_exists($key, $_POST)) {
                            throw new Exception("Please enter your $key ");
                        }
                    }

                    if (empty($_FILES['business_img']['name'])) {
                        throw new Exception("Please upload image of your business");
                    }

                    $business_email = $_POST['business_email'];

                    if (!filter_var($business_email, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception("Invalid business email address");
                    }

                    // check that this business email exists or not...
                    $data = $wpdb->get_results("select business_email from agents where business_email='" . $business_email . "'");

                    if (!empty($data)) {
                        throw new Exception("This business email already exists");
                    }

                    $person_mail = $_POST['person_mail'];

                    if (!filter_var($person_mail, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception("Invalid email address.");
                    }

                    // check that this business email exists or not...
                    $data = $wpdb->get_results("select email from agents where email='" . $person_mail . "'");

                    if (!empty($data)) {
                        throw new Exception("This email already exists");
                    }

                    $password = $_POST['password'];

                    if (strlen($password) < 6) {
                        throw new Exception("Password should be minimum 6 characters");
                    }

                    $con_password = $_POST['con_password'];

                    if ($password != $con_password) {
                        throw new Exception("Password and confirm password should be same");
                    }

                    if (!preg_match('/^[0-9]{10}+$/', $_POST['business_phone'])) {
                        throw new Exception("Invalid phone number for business");
                    }

                    if (!preg_match('/^[0-9]{10}+$/', $_POST['person_number'])) {
                        throw new Exception("Invalid phone number");
                    }

                    $business_url = $_POST['business_site'];

                    if (!filter_var($business_url, FILTER_VALIDATE_URL)) {
                        throw new Exception($business_url . " is not a valid url");
                    }

                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    $image = $_FILES['business_img']['name'];

                    $img_type = pathinfo($image, PATHINFO_EXTENSION);

                    if (!in_array($img_type, $allowedExtensions)) {
                        throw new Exception("Only jpg,jpeg and png formats are allowed");
                    }

                    if ($_FILES['business_img']['size'] > 2 * 1024 * 1024) {
                        throw new Exception("Image size should not exceed more than 2 MB");
                    }

                    $image_name = microtime() . '.' . $img_type;
                    $path = dirname(__DIR__) . "/assets/images/" . $image_name;

                    $insert_agent = ['name' => $_POST['person_name'], 'created_by' => $id, 'email' => $_POST['person_mail'],
                        'password' => $password_hash, 'contact_number' => $_POST['person_number'],
                        'address' => $_POST['person_address'], 'business_name' => $_POST['business_name'],
                        'business_email' => $_POST['business_email'], 'business_phone' => $_POST['business_phone'],
                        'business_address' => $_POST['business_address'], 'business_website' => $_POST['business_site'],
                        'image' => $image_name, 'role' => '3', 'created_at' => Date('Y-m-d h:i:s')];

                    // to start the transaction...
                    $wpdb->query('START TRANSACTION');

                    $insert_agent_res = $wpdb->insert('agents', $insert_agent);

                    if ($insert_agent_res) {

                        if (!move_uploaded_file($_FILES['business_img']['tmp_name'], $path)) {
                            throw new Exception("Image could not be uploaded in the sever directory.Try again");
                        }
                        $wpdb->query('COMMIT');

                        $response = ['status' => Success_Code, 'message' => "Agent Created Successfully"];
                    } else {
                        throw new Exception("Agent could not be created due to internal server error.Try again");
                    }

                    break;

                default:
                    throw new Exception("No Match Found");
                    break;
            }
        }
    } catch (Exception $e) {
        $wpdb->query('ROLLBACK');
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }

} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

echo json_encode($response);
