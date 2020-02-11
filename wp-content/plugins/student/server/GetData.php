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
                    $grade_scheme_sql = "select id,grade_scheme from grade where status='1'";
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
                        echo "<pre>";
                        print_r($_GET);
                        die;
                    break;
                default:
                    throw new Exception("No match found");
                    break;
            }
        }
    } catch (Exception $e) {

    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is Required'];
}

echo json_encode($response);
