<?php
global $wpdb;

if ( !isset( $wpdb ) ) {
    include_once '../../../../wp-config.php';
}

if ( file_exists( dirname( __FILE__, 3 ).'/common/autoload.php' ) ) {
    include_once dirname( __FILE__, 3 ).'/common/autoload.php';
}

// function to verify user...

function verifyUser() {

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    global $payload;

    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser( $payload );
}

if ( !empty( $_GET ) ) {
    try {
        if ( verifyUser() ) {

            switch( $_GET['val'] ) {

                // get the user profile...
                case 'getProfile':

                    // get the user id from payload...
                $id = $payload->userId;

                // query to get the user profile by id...
                $sql = 'select email,image from users where id='.$id;
                $data=$wpdb->get_results($sql);

                // if user profile founds...
                if(!empty($data)){
                    $response=['status'=>Success_Code,'message'=>'User profile fetched Successfully','data'=>$data[0]];
                }
                // if profile not fetched...
                else{
                    throw new Exception("User profile not found due to internal server error");
                }
                break;

                // if no case matches...
                default:
                throw new Exception( 'No match found' );
                break;
            }
        }
    } 
    // catch the exception...
    catch( Exception $e ) {
        $response = ['status'=>Error_Code, 'message'=>$e->getMessage()];
    }
} 

// if user directly access this page...
else {
    $response = ['status'=>Error_Code, 'message'=>'Unauthorized Access'];
}

// returning the json response...
echo json_encode( $response );
?>