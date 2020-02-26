<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function schoolVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return School::verifyUser($payload);
}

function adminVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser($payload);
}

if (!empty($_GET['val'])) {
    try {
        switch ($_GET['val']) {

            case 'getSchoolProfileByAdmin':
                if (adminVerifyUser()) {

                    if (empty($_GET['school_id'])) {
                        throw new Exception("School id is required");
                    }
                    $school_id=base64_decode($_GET['school_id']);

                    getSchoolProfile($wpdb, $school_id);
                }
                break;

            case 'schoolProfile':
                if (schoolVerifyUser()) {
                    $id = $payload->userId;
                    getSchoolProfile($wpdb, $id);
                }

                break;
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }

} else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is Required"];
}

function getSchoolProfile($wpdb, $id)
{

    $school_sql = "select school.*,cntry.id as cntry_id,cntry.name as cntry_name,state.id
    as state_id,state.name as state_name,cities.id as city_id,cities.name as city_name from
    school join countries as cntry on cntry.id=school.countries_id join state on
    state.id=school.state_id join cities on cities.id=school.city_id where school.id=" . $id;

    $certificate_sql = "select * from  school_certificate where school_id=" . $id;
    $certificate_data = $wpdb->get_results($certificate_sql);

    $school_data = $wpdb->get_results($school_sql);
    $response = ['status' => Success_Code, 'message' => 'School Profile Fetched Successfully',
        'data' => $school_data[0], 'certificates' => $certificate_data];

    echo json_encode($response);
    exit;

}

echo json_encode($response);
