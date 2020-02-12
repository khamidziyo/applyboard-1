<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}
if(file_exists(dirname(__FILE__,3)."/common/autoload.php")){
    include_once dirname(__FILE__,3)."/common/autoload.php";
}


if (!empty($_POST)) {
    try {

        $require_arr = ['email', 'password'];
        // if any of the form field is empty...
        foreach ($require_arr as $form_input) {
            if (!array_key_exists($form_input, $_POST)) {
                throw new Exception("Please enter your " . $form_input);
            }
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email');
        }

        // query that fetch the data of user where email exists...
        $sql = "select * from staff where email='" . $email . "'";
        $user = $wpdb->get_results($sql);

        // echo "<pre>";
        // print_r($)
        // if user with that email exists...
        if (!empty($user)) {
            $pass = $user[0]->password;

            // then verify its password...
            if (password_verify($password, $pass)) {

                switch ($user[0]->status) {
                    case '2':
                        throw new Exception("Your account is deactivated.Please contact to admin");
                }

                if ($user[0]->role != "5") {
                    throw new Exception('Unauthorized Access for user ' . $email);
                } else {

                    $payload = [
                        'iat' => time(),
                        'iss' => 'localhost',
                        'exp' => time() + (24 * 60 * 60),
                        'userId' => $user[0]->id,
                    ];
                    $access_token = Jwt::encode($payload, Secret_Key);

                    $data = ['email' => $user[0]->email, 'role' => $user[0]->role, 'token' => $access_token];
                    $response = ['status' => Success_Code, 'message' => "Login Success", 'data' => $data];
                }
            }

            //if password not exists...
            else {
                throw new Exception('Password not exists');
            }
        } else {
            throw new Exception('Email not exists');
        }
    }

    //catch the exception...
     catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.'];
}

echo json_encode($response);
