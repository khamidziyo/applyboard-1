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
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser($payload);
}

include 'QueryFunction.php';

$queries = new QueryFunction($wpdb);

// if value of country state or city is set...
if (isset($_GET['val'])) {

    if (verifyUser()) {
        try {
            switch ($_GET['val']) {

                // if case is country...
                case 'country':

                    // calling function to get all the countries...
                    $countries = $queries->getCountries();

                    $response = ['status' => Success_Code, 'message' => 'Data fetched successfully', 'countries' => $countries];

                    break;

                // if case is state...
                case 'state':

                    // decoding the encrypted country id...
                    $cntry_id = base64_decode($_GET['data']);

                    // calling function to get states of particular country...
                    $states = $queries->getStates($cntry_id);

                    $response = ['status' => Success_Code, 'message' => 'Data fetched successfully', 'states' => $states];

                    break;

                // if case is city...
                case 'city':

                    // decoding the state id...
                    $state_id = base64_decode($_GET['data']);

                    // calling function to get all cities of particular state...
                    $cities = $queries->getCities($state_id);
                    $response = ['status' => Success_Code, 'message' => 'Data fetched successfully', 'cities' => $cities];

                    break;

                // if no such case found...
                default:
                    throw new Exception('Unauthorized Access.No match found');
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
