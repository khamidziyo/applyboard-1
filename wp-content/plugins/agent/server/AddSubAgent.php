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
    return Agent::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {
        if (verifyUser()) {

            switch ($_POST['val']) {
                case 'addSubAgent':
                    $id = $payload->userId;

                    $require_arr = ['email', 'password', 'con_password'];

                    foreach ($require_arr as $form_input) {

                        if (!array_key_exists($form_input, $_POST)) {
                            throw new Exception("Please enter " . $form_input);
                        }
                    }
                    $email = trim($_POST['email']);

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception("Invalid Email address");
                    }

                    $email_exists = $wpdb->get_results("select email from agents where email='" . $email . "'");
                    if (!empty($email_exists)) {
                        throw new Exception("This email already exists.Try another");
                    }

                    $password = trim($_POST['password']);
                    if (strlen($password) < 6) {
                        throw new Exception("Password length should not be less than six characters");
                    }

                    $con_password = trim($_POST['con_password']);
                    if ($password != $con_password) {
                        throw new Exception("Password and confirm password should be same");
                    }

                    // if the user creates a sub agent with no extra permissions...
                    $permission = '0';

                    // if agent creates a subagent with extra permissions...
                    if (isset($_POST['permission'])) {
                        $permission = $_POST['permission'];
                    }

                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $ins_agent_arr = ['email' => $email, 'created_by' => $id, 'password' => $password,
                        'role' => '4', 'permission' => $permission, 'created_at' => Date('Y-m-d h:i:s')];
                    $ins_agent_res = $wpdb->insert('agents', $ins_agent_arr);

                    if ($ins_agent_res) {
                        $response = ['status' => Success_Code, 'message' => 'Sub agent created successfully'];
                    } else {
                        throw new Exception("Sub Agent not created due to internal server error");
                    }
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
