<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

// function to verify user...

function staffVerifyUser()
{
    global $payload;

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Staff::verifyUser($payload);
}

if (!empty($_GET['val'])) {
    if (staffVerifyUser()) {
        try {
            switch ($_GET['val']) {

                case 'getStudentByStaff':

                    if (empty($_GET['student'])) {
                        throw new Exception("Student id is required");
                    }

                    $stu_id = base64_decode($_GET['student']);
                    $sql = "select users.*,language.name as lang_name,countries.name as cntry_name,
                    grade.name as grade_name,applications.intake from users join language on language.id=users.language_prior
                    join countries on countries.id=users.nationality join grade on grade.id=users.grade_id
                     join applications on applications.student_id=users.id where users.id=" . $stu_id;

                    $user = $wpdb->get_results($sql);
                    $exams = json_decode($user[0]->exam, true);

                    foreach ($exams as $exam_id => $sub_arr) {
                        $exam_name = $wpdb->get_results("select name from exams where id=" . $exam_id);
                        $exam[$exam_name[0]->name] = $sub_arr;
                    }

                    $intake = json_decode($user[0]->intake, true);
                    $month = $intake[0];
                    $year = $intake[1];
                    $intake_month = $wpdb->get_results("select name from intakes where id=" . $month);

                    $intake = ['month' => $intake_month[0]->name, 'year' => $year];

                    $documents = $wpdb->get_results("select * from user_documents where user_id=" . $stu_id);

                    if (!empty($wpdb->last_error)) {
                        throw new Exception("Student id is invalid");
                    }
                    $response = ['status' => Success_Code, 'message' => 'Student Detail Fetched Successfully',
                        'student' => $user[0], 'document' => $documents, 'exams' => json_encode($exam),
                        'intake' => json_encode($intake)];

                    break;

                default:
                    throw new Exception("No case matches");
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
