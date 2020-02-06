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

if (!empty($_GET['val'])) {
    try {
        if (verifyUser()) {
            switch ($_GET['val']) {

                case 'editUser':
                    if (empty($_GET['student'])) {
                        throw new Exception("Student id is required");
                    }
                    $student_id = base64_decode($_GET['student']);

                    if (empty($student_id)) {
                        throw new Exception("Invalid Student id");
                    }

                    $stu_exist = $wpdb->get_results("select * from users where id=" . $student_id);

                    if (empty($stu_exist)) {
                        throw new Exception("Invalid student id.The student does not exist");
                    }

                    $sql = "select id,document from user_documents where user_id=" . $student_id;

                    $student_docs = $wpdb->get_results($sql);

                    $response = ['status' => Success_Code, 'message' => 'Student Profile fetched successfully',
                        'data' => $stu_exist[0],'documents' => $student_docs];

                    break;

                default:
                    throw new Exception("No match found");
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
