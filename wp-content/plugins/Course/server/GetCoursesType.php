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

            $month = Date('m');
            // echo $month;
            // die;

            $c_intake_next = $wpdb->get_results("select * from intakes where status='1' && id>" . $month);
            $nxt_intakes = [];
            $previous_intakes = [];

            if (!empty($c_intake_next)) {
                foreach ($c_intake_next as $key => $obj) {
                    $obj->year = Date('Y');
                    $nxt_intakes[] = $obj;
                }
            }

            $c_intake_previous = $wpdb->get_results("select * from intakes where status='1' && id<=" . $month);

            if (!empty($c_intake_previous)) {
                foreach ($c_intake_previous as $key => $obj) {
                    $obj->year = Date('Y') + 1;
                    $previous_intakes[] = $obj;
                }
            }

            $c_intakes = array_merge($nxt_intakes, $previous_intakes);

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
