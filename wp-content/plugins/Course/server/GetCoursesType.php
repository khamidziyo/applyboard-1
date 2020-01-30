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

if (!empty($_GET['data'])) {

    try {
        if (verifyUser()) {

            $type_query = "select * from type";
            $category_query = "select * from category";
            $language_query = "select * from language";

            $c_types = $wpdb->get_results($type_query);
            $c_categories = $wpdb->get_results($category_query);
            $c_language = $wpdb->get_results($language_query);

            $response = ['status' => 200, 'c_type' => $c_types, 'c_category' => $c_categories, 'c_language' => $c_language];

        }
    } catch (Exception $e) {
        $response = ['status' => 400, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => 400, 'message' => 'Unauthorized Access'];
}
echo json_encode($response);
