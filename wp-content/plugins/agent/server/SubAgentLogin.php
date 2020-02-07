<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . "/common/autoload.php")) {
    include_once dirname(__FILE__, 3) . "/common/autoload.php";
}

if (!empty($_POST)) {
    try {

        if (empty($_POST['email'])) {
            throw new Exception("Please enter your email address");
        }

        if (empty($_POST['password'])) {
            throw new Exception("Please enter your password");
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $sub_agent_exist = $wpdb->get_results("select id,email,password,status,role from agents
        where email='" . $email . "' && role='4'");

        if (empty($sub_agent_exist[0])) {
            throw new Exception("This email address does not exist");
        }

        $hash_pass = $sub_agent_exist[0]->password;

        // then verify its password...
        if (!password_verify($password, $hash_pass)) {
            throw new Exception('This password does not exist');
        }

        if ($sub_agent_exist[0]->status == "2") {
            throw new Exception("Your account is deactivated.Please contact to site admin");
        }

        $payload = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => time() + (24 * 60 * 60),
            'userId' => $sub_agent_exist[0]->id,
        ];
        $access_token = Jwt::encode($payload, Secret_Key);

        $data = ['email' => $sub_agent_exist[0]->email, 'role' => $sub_agent_exist[0]->role, 
        'token' => $access_token];
        
        $response = ['status' => Success_Code, 'message' => "Login Success", 'data' => $data];

    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }

} else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Email and password is required"];
}
echo json_encode($response);
