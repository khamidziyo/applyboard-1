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
            $c_types = $wpdb->get_results("select * from type where status='1'");
            $c_categories = $wpdb->get_results("select * from category where status='1'");
            $c_language = $wpdb->get_results("select * from language where status='1'");
            $c_intakes = $wpdb->get_results("select * from intakes where status='1'");

            $response = ['status' => 200, 'c_type' => $c_types, 'c_category' => $c_categories,
                'c_language' => $c_language, 'c_intake' => $c_intakes];

        }
    } catch (Exception $e) {
        $response = ['status' => 400, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => 400, 'message' => 'Unauthorized Access'];
}
echo json_encode($response);
