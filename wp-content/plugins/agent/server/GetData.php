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

            // sql to get all the countries...
            $country_sql = "select id,name from countries";
            $country_data = $wpdb->get_results($country_sql);

            // sql to get all the grades...
            $grade_scheme_sql = "select id,grade_scheme from grade where status='1'";
            $grade_data = $wpdb->get_results($grade_scheme_sql);

            // sql to get all the exams...
            $lang_sql = "select id,name from language";
            $languages = $wpdb->get_results($lang_sql);

            // storing all data in an array...
            $response = ['status' => Success_Code, 'cntry_data' => $country_data,
                'grade' => $grade_data, 'languages' => $languages];
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is Required'];
}

echo json_encode($response);
