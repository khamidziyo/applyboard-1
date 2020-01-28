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
    return Student::verifyUser( $payload );
}

if ( !empty( $_POST['val'] ) ) {
    if ( verifyUser() ) {

        try {
            $response = [];

            // switch case to match the user profile...
            switch( $_POST['val'] ) {

                // user profile cases...
                case 'studentProfile':

                $id = base64_decode( $_POST['id'] );
                $sql = 'select id,email,image,status from users where id='.$id;
                $data = $wpdb->get_results( $sql );

                if ( !empty( $data ) ) {
                    $response = ['status'=>Success_Code, 'message'=>'User profile fetched successfully', 'data'=>$data[0]];
                } else {
                    throw new Exception( 'Unauthorized Access' );
                }
                break;

                // if no case matches...
                default:
                throw new Exception( 'No match found' );
                break;
            }
        } catch( Exception $e ) {
            $response = ['status'=>Error_Code, 'message'=>$e->getMessage()];
        }
    }
} else {
    $response = ['status'=>Error_Code, 'message'=>'Unauthorized Access'];
}
echo json_encode( $response );

?>