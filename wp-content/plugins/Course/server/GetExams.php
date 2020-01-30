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

if (!empty($_GET)) {

    try {
        if (verifyUser()) {

            switch ($_GET['val']) {

                case 'getAllExams':

                    if(empty($_GET['lang_id'])){
                        throw new Exception("Please select the language of instruction first");
                    }

                    $language = $_GET['lang_id'];

                    $sql = "select * from exams where language_id=" . $language;

                    $data = $wpdb->get_results($sql);
                   
                    $response = ['status' => Success_Code, 'message' => 'Exams fetched successfully', 'data' => $data];
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
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access"];
}
echo json_encode($response);
