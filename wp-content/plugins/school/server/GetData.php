<?php

global $wpdb;

if ( !isset( $wpdb ) ) {
    include_once '../../../../wp-config.php';
}

if ( file_exists( dirname( __FILE__, 3 ).'/common/autoload.php' ) ) {
    include_once dirname( __FILE__, 3 ).'/common/autoload.php';
}

function verifyUser() {
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser( $payload );
}

include 'QueryFunction.php';

$queries = new QueryFunction( $wpdb );

// if value of country state or city is set...
if ( isset( $_GET['val'] ) ) {

    if ( verifyUser() ) {

        switch( $_GET['val'] ) {

            // if case is country...
            case 'country':

            // calling function to get all the countries...
            $results = $queries->getCountries();

            // if countries are not empty...
            if ( !empty( $results ) ) {
                $response = ['status'=>200, 'message'=>'Data fetched successfully', 'data'=>$results];
            }

            // if no country found in database...
            else {
                $response = ['status'=>400, 'message'=>'No data found'];

            }

            // returning json response...
            echo json_encode( $response );
            break;

            // if case is state...
            case 'state':

            try {
                // decoding the encrypted country id...
                $cntry_id = base64_decode( $_GET['data']);

                // calling function to get states of particular country...
                $results = $queries->getStates( $cntry_id );

                // if states found in the database...
                if ( !empty( $results ) ) {
                    $response = ['status'=>200, 'message'=>'Data fetched successfully', 'data'=>$results];
                }
                // if no state found in database...
                else {
                    throw new Exception( 'No state found' );
                }
            } catch( Exception $e ) {
                $response = ['status'=>400, 'message'=>$e->getMessage()];
            }
            // returning json response...
            echo json_encode( $response );

            break;

            // if case is city...
            case 'city':

            try {
                // decoding the encrypted state id...
                $state_id = base64_decode( $_GET['data']);

                // calling function to get all cities of particular state...
                $results = $queries->getCities( $state_id );

                // if cities found in database...
                if ( !empty( $results ) ) {
                    $response = ['status'=>200, 'message'=>'Data fetched successfully', 'data'=>$results];
                }

                // if no city found in database...
                else {
                    throw new Exception( 'No city found' );
                }
            } catch( Exception $e ) {
                $response = ['status'=>400, 'message'=>$e->getMessage()];
            }
            echo json_encode( $response );

            break;

            // if no such case found...
            default:
            $response = ['status'=>400, 'message'=>'No match found'];

            echo json_encode( $response );

            break;

        }
    }
}

?>