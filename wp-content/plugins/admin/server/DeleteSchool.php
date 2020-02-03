<?php

global $wpdb;

if ( !isset( $wpdb ) ) {
    include_once '../../../../wp-config.php';
}

if ( file_exists( dirname( __FILE__, 3 ).'/common/autoload.php' ) ) {
    include_once dirname( __FILE__, 3 ).'/common/autoload.php';
}

// function that checks for a user has permission to view the page...
function verifyUser() {

    // decoding the authorization token...
    $payload = JwtToken::getBearerToken();

    // verify the userid getting from payload and verify its role...
    return Admin::verifyUser( $payload );
}

if ( !empty( $_POST ) ) {
    $response = [];

    try {

        // if user verified successfully...
        if ( verifyUser() ) {

            // decode the school id that is to be deleteed...
            $school_id = base64_decode( $_POST['s_id'] );

            // get all the certificates of particular school...
            $certificate_sql = 'select document from school_certificate where school_id='.$school_id;
            // echo $certificate_sql;

            //get the profile and cover image of school...
            $image_sql = 'select profile_image,cover_image from school where id='.$school_id;

            // query to get all the images...
            $images = $wpdb->get_results( $image_sql );

            // query to get all the certificates...
            $certificates = $wpdb->get_results( $certificate_sql );

            // delete query to delete the school...
            $delete_school_sql = 'DELETE  FROM school WHERE id ='.$school_id;

            // deleting the profile image from folder...
            if ( !unlink( dirname( __FILE__, 3 ).'/school/assets/images/'.$images[0]->profile_image ) ) {
                throw new Exception( 'Intrnal server error in deleting profile image' );
            }

            // deleting the cover image from folder...
            if ( !unlink( dirname( __FILE__, 3 ).'/school/assets/images/'.$images[0]->cover_image ) ) {
                throw new Exception( 'Intrnal server error in deleting school cover image' );
            }

            // deleting the certificates of school...
            if ( !empty( $certificates ) ) {
                foreach ( $certificates as $key=>$obj ) {
                    if ( !unlink( dirname( __FILE__, 3 ).'/school/assets/certificates/'.$obj->document ) ) {
                        throw new Exception( 'Intrnal server error in deleting school certificates' );
                    }
                }
            }

            // query to delete the school...
            $del = $wpdb->query( $delete_school_sql );

            // if school deleted successfully...
            if ( $del ) {
                $response = ['status'=>Success_Code, 'message'=>'School deleted successfully'];
            } else {            //     // echo $del;

                $response = ['status'=>400, 'message'=>'School not deleted due to internal server error'];
            }
        }

        // catch the message on exception...
    } catch( Exception $e ) {
        $response = ['status'=>Error_Code, 'message'=>$e->getMessage()];
    }
} else {
    $response = ['status'=>Error_Code, 'message'=>'Unauthorized Access'];
}

// returning json response...
echo json_encode( $response );
?>