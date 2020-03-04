<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (!empty($_POST['type'])) {

    try {

        if (empty($_POST['u_mail'])) {
            throw new Exception("Please enter your email registered with applypartner");
        }

        $email = $_POST['u_mail'];

        // if a mail is invalid...
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email');
        }

        switch ($_POST['type']) {

            case 'admin':
                sendMailToUser($wpdb, $email, '2');
                break;

            case 'student':
                sendMailToUser($wpdb, $email, '1');

                break;

            case 'agent':
                sendMailToAgent($wpdb, $email, '3');
                break;

            case 'subagent':
                sendMailToAgent($wpdb, $email, '4');
                break;

            case 'staff':
                sendMailToStaff($wpdb, $email, '5');
                break;
        }

    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }

} else {
    $response = ['status' => Error_Code, 'message' => "Email is required"];
}

function sendMailToUser($wpdb, $email, $role)
{
    $sql = "select id,email,role from users where email='" . $email . "' && role='" . $role . "'";

    $user = $wpdb->get_results($sql);

    if (!empty($user)) {
        $user_id = $user[0]->id;

        $token = md5(rand(100000, 100000000));

        $wpdb->update('users', ['forgot_password_token' => $token], ['id' => $user_id]);

        switch ($user[0]->role) {

            // if student forgots the password...
            case '1':
                // url of the forgot password recovery page...
                $url = get_home_url() . "/index.php/reset-password/?tok=" . $token . "&&user=" . base64_encode($user_id) . "&&case=student";
                break;

            // if admin forgots the password...
            case '2':
                // url of the forgot password recovery page...
                $url = get_home_url() . "/index.php/reset-password/?tok=" . $token . "&&user=" . base64_encode($user_id) . "&&case=admin";
                break;
        }

        // calling function to send the mail to reset password...
        sendMail($url, $email);

    } else {
        throw new Exception('This email does not exists');
    }
}

function sendMailToAgent($wpdb, $email, $role)
{
    $sql = "select id,email,role from agents where email='" . $email . "' && role='" . $role . "'";

    $agent = $wpdb->get_results($sql);

    if (!empty($agent)) {
        $agent_id = $agent[0]->id;

        $token = md5(rand(100000, 100000000));

        $wpdb->update('agents', ['forgot_password_token' => $token], ['id' => $agent_id]);
    } else {
        throw new Exception('This email does not exists');
    }

    switch ($agent[0]->role) {

        // if agent forgots the password...
        case '3':
            // url of the forgot password recovery page...
            $url = get_home_url() . "/index.php/reset-password/?tok=" . $token . "&&user=" . base64_encode($agent_id) . "&&case=agent";
            break;

        // if subagent forgots the password...
        case '4':
            // url of the forgot password recovery page...
            $url = get_home_url() . "/index.php/reset-password/?tok=" . $token . "&&user=" . base64_encode($agent_id) . "&&case=subagent";
            break;

    }

    // calling function to send the mail to reset password...
    sendMail($url, $email);
}

// function that sends the mail to staff to reset the password...
function sendMailToStaff($wpdb, $email, $role)
{

    $sql = "select id,email,role from staff where email='" . $email . "' && role='" . $role . "'";

    $staff = $wpdb->get_results($sql);

    if (!empty($staff)) {
        $staff_id = $staff[0]->id;

        $token = md5(rand(100000, 100000000));

        $wpdb->update('staff', ['forgot_password_token' => $token], ['id' => $staff_id]);

    } else {
        throw new Exception('This email does not exists');
    }

    $url = get_home_url() . "/index.php/reset-password/?tok=" . $token . "&&user=" . base64_encode($staff_id) . "&&case=staff";

    // calling function to send the mail to reset password...
    sendMail($url, $email);

}

// function that sends the mail to user to reset the password...
function sendMail($url, $email)
{
    // html to render when mail will be sent to user...
    $msg = "<h1>Hello " . $email . "\n Welcome To Apply board.</h1>
             <p><a href='" . $url . "'>Click Here</a> Please click on the below link to create new password.</p>
            <a class='btn btn-primary' href=" . $url . "></a>";

    $subject = "<h1>Forgot Password Applyboard</h1>";

    // sending mail to user...
    $mail_res = wp_mail($email, $subject, $msg);

    // if mail success...
    if ($mail_res) {
        $response = ['status' => Success_Code, 'message' => 'Please check your mail.Mail sent'];
    } else {
        throw new Exception('Mail not sent due to internal server error');
    }
    echo json_encode($response);
    exit;
}

echo json_encode($response);
