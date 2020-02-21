<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

// function to verify user...

function studentVerifyUser()
{
    global $payload;

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser($payload);
}

if (!empty($_GET)) {
    try {
        if (studentVerifyUser()) {
            switch ($_GET['val']) {
                case 'getDataByStudent':

                    // sql to get all the countries...
                    $country_sql = "select id,name from countries";
                    $country_data = $wpdb->get_results($country_sql);

                    // sql to get all the grades...
                    $grade_scheme_sql = "select id,name from grade where status='1'";
                    $grade_data = $wpdb->get_results($grade_scheme_sql);

                    // sql to get all the exams...
                    $lang_sql = "select id,name from language";
                    $languages = $wpdb->get_results($lang_sql);

                    $category_sql = "select id,name from category";
                    $categories = $wpdb->get_results($category_sql);

                    $discipline_sql = "select id,name from type";
                    $discipline_data = $wpdb->get_results($discipline_sql);

                    // storing all data in an array...
                    $response = ['status' => Success_Code, 'cntry_data' => $country_data,
                        'grade' => $grade_data, 'languages' => $languages,
                        'categories' => $categories, 'disciplines' => $discipline_data];
                    break;

                case 'getExams':

                    // to get the language id...
                    if (empty($_GET['id'])) {
                        throw new Exception("Language id is required");
                    }
                    $lang_id = $_GET['id'];

                    // get the exams based on language id...
                    $exams = $wpdb->get_results("select id,name from exams where language_id=" . $lang_id);
                    if (!empty($exams)) {
                        $response = ['status' => Success_Code, 'message' => 'Exams fetched Successfully',
                            'exam_data' => $exams];
                    }

                    // if no exam found ...
                    else {
                        throw new Exception("No exams found");
                    }
                    break;

                case 'getGradeScheme':
                    if (empty($_GET['id'])) {
                        throw new Exception("Please select the language first");
                    }
                    $id = $_GET['id'];

                    // sql to get all the exams of specific language...
                    $grade_sql = "select id,grade_scheme from grade where id=" . $id;
                    $grade_data = $wpdb->get_results($grade_sql);

                    // storing all data in an array...
                    $response = ['status' => Success_Code, 'message' => 'Grade scheme fetched successfully',
                        'grade_data' => json_decode($grade_data[0]->grade_scheme)];
                    break;
                // if no case matches...
                default:
                    throw new Exception("No match found");
                    break;
            }
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is Required'];
}

// return the json response...
echo json_encode($response);
