<?php
global $wpdb;

if ( !isset( $wpdb ) ) {
    include_once '../../../../wp-config.php';
}

if ( file_exists( dirname( __FILE__, 3 ).'/common/autoload.php' ) ) {
    include_once dirname( __FILE__, 3 ).'/common/autoload.php';
}


function verifyUser() {
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser( $payload );
}

$allowedExtensions = ['jpg', 'jpeg', 'png'];
$path = dirname( __DIR__, 1 );

if ( !empty( $_POST['email'] ) ) {
    if ( verifyUser() ) {
        try {

            // get the user email...
            $email = $_POST['email'];
            $user_id = $payload->userId;

            // validating the user mail...
            if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                throw new Exception( 'Invalid email address' );
            }

            // query to get email...
            $sql = "select email from users where email='".$email."' && id!=".$user_id;
            $user = $wpdb->get_results( $sql );

            // if the email not already exists...
            if ( !empty( $user ) ) {
                throw new Exception( 'This email already exists' );
            }

            // get old image...
            $image_sql = 'select image from users where id='.$user_id;

            $old_image = $wpdb->get_results( $image_sql );

            // if user updates the image...
            if ( !empty( $_FILES['admin_image']['name'] ) ) {

                $image_name = $_FILES['admin_image']['name'];

                // get the type of image...
                $type = pathinfo( $image_name, PATHINFO_EXTENSION );

                $size = $_FILES['admin_image']['size'];

                // if image size exceeds 2 MB...
                if ( $size>2*1024*1024 ) {
                    throw new Exception( 'Image should not exceed more than 2 MB' );
                }
                // if image type is not allowed...
                if ( !in_array( $type, $allowedExtensions ) ) {
                    throw new Exception( 'Only jpg,jpeg and png formats are allowed' );
                }

                // if oldimage exists...
                if ( !empty( $old_image[0]->image ) ) {

                    // deleting the image from folder...
                    if ( !unlink( $path.'/assets/images/'.$old_image[0]->image ) ) {
                        throw new Exception( 'Image not deleted due to internal server error' );
                    }
                }

                // generating a new image name using time function...
                $image_name = microtime().'.'.$type;

                // echo $path.'/assets/images/'.$image_name ;die;
                // upload image to folder...
                if ( !move_uploaded_file( $_FILES['admin_image']['tmp_name'],$path.'/assets/images/'.$image_name ) ) {
                    throw new Exception( 'File not uploaded' );
                }
            }

            // if user not changes the image...
            else {
                $image_name = $old_image[0]->image;
            }

            // update query to update profile...
            $wpdb->update( 'users', ['email'=>$email, 'image'=>$image_name], ['id'=>$user_id] );
            $response = ['status'=>Success_Code, 'message'=>'Profile Updated Successfully'];

        }

        // if any exception occurs...
        catch( Exception $e ) {
            $response = ['status'=>Error_Code, 'message'=>$e->getMessage()];
        }
    }

    // returning the json response...
    echo json_encode( $response );
}
?>