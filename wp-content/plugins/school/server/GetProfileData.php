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

                // get country state and cities when user updates the profile...
                case 'getData':
                    // if country id is empty...
                    if (empty($_GET['cntry_id'])) {
                        throw new Exception("Country is required");
                    }

                    //if state id is empty...
                    if (empty($_GET['state_id'])) {
                        throw new Exception("State is required");
                    }

                    $country_id = $_GET['cntry_id'];
                    $state_id = $_GET['state_id'];

                    // query to get the countries...
                    $countries = $wpdb->get_results("select * from countries");

                    // query to get the states...
                    $states = $wpdb->get_results("select id,name from state where countries_id=" . $country_id);

                    // query to get the cities...
                    $cities = $wpdb->get_results("select id,name,postal_code from cities where state_id=" . $state_id);

                    // response to return the profile data to update the school...
                    $response = ['status' => Success_Code, 'message' => 'Profile Data Fetched Successfully',
                        'countries' => $countries, 'states' => $states, 'cities' => $cities,
                    ];
                    break;

                // to get the states when user selects any other country...
                case 'getStates':
                    $country_id = $_GET['cntry_id'];
                    // query to get the states...
                    $states = $wpdb->get_results("select id,name from state where countries_id=" . $country_id);
                    $response = ['status' => Success_Code, 'message' => 'States Fetched successfully', 'states' => $states];
                    break;

                // to get the cities when user selects any other state...
                case 'getCities':
                    $state_id = $_GET['state_id'];

                    // query to get the cities...
                    $cities = $wpdb->get_results("select id,name from cities where state_id=" . $state_id);

                    $response = ['status' => Success_Code, 'message' => 'Cities Fetched successfully', 'cities' => $cities];

                    break;

                // to get the pincode when user selects any other city...
                case 'getPostalCode':
                    $city_id = $_GET['city_id'];

                    // query to get the cities...
                    $pincode = $wpdb->get_results("select postal_code from cities where id=" . $city_id);
                    $response = ['status' => Success_Code, 'message' => 'Pincode Fetched successfully', 'pincode' => $pincode[0]];

                    break;

                default:
                    throw new Exception("No match found");
                    break;
            }

        }
    }

    // if any exception occurs...
     catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
}

// if user directly access this page...
else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}

// returning the json response...
echo json_encode($response);
