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
    return School::verifyUser($payload);
}

if (!empty($_GET['val'])) {

    try {
        if (verifyUser()) {

            switch ($_GET['val']) {

                // case to get user detail to view the user who send the application
                case 'getUserDetail':

                    // if user id not exists in request..
                    if (empty($_GET['id'])) {
                        throw new Exception("User id is required");
                    }

                    $user_id = base64_decode($_GET['id']);
                    $application_id = base64_decode($_GET['app_id']);

                    // sql to get the user...
                    $sql = "select *,grade.name as grade_name,countries.name as cntry_name,language.name as lang_name from users
                     left join language on language.id=users.language_prior left join grade on
                     grade.id= users.grade_id left join countries on countries.id=users.nationality
                     where users.id=" . $user_id;

                    $user_data = $wpdb->get_results($sql);

                    // if no data found...
                    if (empty($user_data)) {
                        throw new Exception("No data found.Invalid User");
                    }

                    $documents = $wpdb->get_results("select * from user_documents where user_id=" . $user_id);

                    $exam_data = json_decode($user_data[0]->exam, true);

                    $exam_ids = array_keys($exam_data);

                    $application_status = $wpdb->get_results("select status from applications where
                     student_id=" . $user_id . " && id=" . $application_id);

                    $exam_sql = "select id,name from exams where id in (" . implode(",", $exam_ids) . ")";
                    $exam_data = $wpdb->get_results($exam_sql);

                    $response = ['status' => Success_Code, 'message' => 'User Detail fetched successfully',
                        'user' => $user_data[0], 'exam' => $exam_data, 'application' => $application_status[0],
                        'documents' => $documents,
                    ];

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

// returning the json response...
echo json_encode($response);
