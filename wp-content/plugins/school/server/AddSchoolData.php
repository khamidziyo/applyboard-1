<?php

$error = '';

$allowedExtensions = ['jpeg', 'jpg', 'png'];
$path = dirname( __DIR__, 1 );
$accomodation = 0;
$work_studying = 0;
$offer_letter = 0;
$living_cost;
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

if ( !empty( $_POST ) ) {

    if ( verifyUser() ) {

        // if the school provides accomodation facility...
        if ( isset( $_POST['accomodation'] ) ) {
            $accomodation = 1;
            $living_cost = $_POST['living_cost'];
        }

        // if school permits work while studying...
        if ( isset( $_POST['work_studying'] ) ) {
            $work_studying = 1;
        }

        // if school provides conditional offer letter...
        if ( isset( $_POST['offer_letter'] ) ) {
            $offer_letter = 1;
        }

        // if any form field is empty then returning error...
        foreach ( $_POST as $key=>$val ) {
            if ( empty( $val ) ) {
                $error = $key.' is required';
                $response = ['status'=>400, 'message'=>$error];
                // return error response...
                echo json_encode( $response );
                exit();
            }
        }

        try {

            // when admin updates the school...
            if ( !empty( $_POST['school_id'] ) ) {
                $school_id = base64_decode( $_POST['school_id'] );
                // if school name already exists...
                $name = $wpdb->get_results( "select name from school where name='".$_POST['name']."' && id!=".$school_id );
                if ( !empty( $name ) ) {
                    throw new Exception( 'The school with same name already exists' );
                }
            } else {
                // if school name already exists...
                $name = $wpdb->get_results( 'select name from school where name="'.$_POST['name'].'"' );
                if ( !empty( $name ) ) {
                    throw new Exception( 'The school with same name already exists' );
                }
            }

            // if a mail is invalid...
            if ( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) ) {
                throw new Exception( 'Invalid email' );
            }

            if ( !empty( $_POST['school_id'] ) ) {
                $sql = "select email from school where email='".$_POST['email']."' && id!=".$school_id;
                $data = $wpdb->get_results( $sql );
                if ( !empty( $data[0]->email ) ) {
                    throw new Exception( 'Email already exists' );
                }
            } else {
                // if a mail already exists...
                $email_obj = $wpdb->get_results( 'select email from school where email="'.$_POST['email'].'"' );
                if ( !empty( $email_obj ) ) {
                    throw new Exception( 'The school with same email already exists' );
                }
            }

            // if phone number length is less than 10 digits...
            if ( strlen( $_POST['number'] ) < 10 ) {
                throw new Exception( 'Invalid mobile number' );
            }

            // to start the transaction...
            $wpdb->query( 'START TRANSACTION' );

            if ( !empty( $_FILES['profile_image']['name'] ) ) {

                if ( !empty( $_POST['pro_image'] ) ) {
                    if ( !unlink( $path.'/assets/images/'.$_POST['pro_image'] ) ) {
                        throw new Exception( 'Unable to delete school profile image due to server error' );
                    }
                }
                //validating profile image size for not greater than 2MB...
                if ( $_FILES['profile_image']['size']>2*1024*1024 ) {
                    throw new Exception( 'profile image size should not be more than 2 MB' );
                }

                //validating profile image type of only allowed types...
                if ( !in_array( pathinfo( $_FILES['profile_image']['name'], PATHINFO_EXTENSION ), $allowedExtensions ) ) {
                    throw new Exception( 'Only jpg,jpeg and png formats are allowed' );
                }

                // generating a new image name using time function...
                $profile_image_name = microtime().'.'.pathinfo( $_FILES['profile_image']['name'], PATHINFO_EXTENSION );

                // upload profile image to folder...
                if ( !move_uploaded_file( $_FILES['profile_image']['tmp_name'], $path.'/assets/images/'.$profile_image_name ) ) {
                    throw new Exception( 'File not uploaded' );
                }
            } else {
                $profile_image_name = $_POST['pro_image'];
            }

            if ( !empty( $_FILES['cover_image']['name'] ) ) {

                if ( !empty( $_POST['co_image'] ) ) {
                    if ( !unlink( $path.'/assets/images/'.$_POST['co_image'] ) ) {
                        throw new Exception( 'Unable to delete school cover image due to server error' );
                    }
                }

                //validating cover image size for not greater than 2MB...
                if ( $_FILES['cover_image']['size']>2*1024*1024 ) {
                    throw new Exception( 'cover image  size is more than 2 MB' );
                }

                //validating cover image type of only allowed types...
                if ( !in_array( pathinfo( $_FILES['cover_image']['name'], PATHINFO_EXTENSION ), $allowedExtensions ) ) {
                    throw new Exception( 'Only jpg,jpeg and png formats are allowed' );
                }

                // generating a new image name using time function...
                $cover_image_name = microtime().'.'.pathinfo( $_FILES['cover_image']['name'], PATHINFO_EXTENSION );

                // upload cover image to folder...
                if ( !move_uploaded_file( $_FILES['cover_image']['tmp_name'], $path.'/assets/images/'.$cover_image_name ) ) {
                    throw new Exception( 'File not uploaded' );
                }
            } else {
                $cover_image_name = $_POST['co_image'];
            }

            // creating new password...
            $rand_password=rand();
            $password = md5( $rand_password);

            //creating token to verify...
            $token = base64_encode( rand( 1000, 1000000 ) );

            // updating the school data on edit form...
            if ( !empty( $_POST['school_id'] ) ) {

                $update_arr = ['user_id'=>$payload->userId, 'name'=>$_POST['name'],
                'email'=>$_POST['email'], 'address'=>$_POST['address'], 'number'=>$_POST['number'],
                'description'=>$_POST['description'], 'countries_id'=>$_POST['country'],
                'state_id'=>$_POST['state'], 'city_id'=>$_POST['city'], 'type'=>$_POST['school_type'],
                'postal_code'=>$_POST['pin_code'], 'accomodation'=>$accomodation, 'living_cost'=>$living_cost,
                'work_studying'=>$work_studying, 'offer_letter'=>$offer_letter,
                'profile_image'=>$profile_image_name, 'cover_image'=>$cover_image_name,
                'updated_at'=>date( 'Y-m-d h:i:s' )];

   
                // updating school data to school table...
                $result = $wpdb->update( 'school', $update_arr, ['id'=>$school_id] );
                if ( $result ) {

                    $data = $wpdb->get_results( 'select id,document from school_certificate where school_id='.$school_id );
                    foreach ( $data as $key=>$obj ) {
                        $certificate_arr[] = $obj->document;
                    }
                    foreach ( $certificate_arr as $key=>$image_name ) {
                        if ( !in_array( $image_name, $_POST['certificates'] ) ) {
    
                            if ( !$wpdb->query("DELETE  FROM school_certificate WHERE document ='".$image_name."'" ) ) {
                                throw new Exception( 'Inernal server error in removing previous certificates' );
                            }
                            if ( !unlink( $path.'/assets/certificates/'.$image_name ) ) {
                                throw new Exception( 'Certificated not deleted due to internal server error' );
                            }
                        }
                    }

                    if ( !empty( $_FILES['document']['name'][0] ) ) {
                        // calling Function to upload school certificates...
                        uploadSchoolCertificates( $school_id, $wpdb, $path, '', '', 'update' );
                    } else {
                        $wpdb->query( 'COMMIT' );
                        $response = ['status'=>Success_Code, 'message'=>'School Updated Successfully'];
                    }
                } else {
                    $response = ['status'=>Error_Code, 'message'=>'School not updated due to internal server error'];
                }

                echo json_encode( $response );
                exit;

            } else {

                $inset_arr = ['user_id'=>$payload->userId, 'name'=>$_POST['name'], 'email'=>$_POST['email'], 'password'=>$password, 'address'=>$_POST['address'],
                'number'=>$_POST['number'], 'description'=>$_POST['description'], 'countries_id'=>$_POST['country'], 'state_id'=>$_POST['state'],
                'city_id'=>$_POST['city'], 'type'=>$_POST['school_type'], 'postal_code'=>$_POST['pin_code'],
                'accomodation'=>$accomodation, 'living_cost'=>$living_cost,
                'work_studying'=>$work_studying, 'offer_letter'=>$offer_letter, 'profile_image'=>$profile_image_name, 'cover_image'=>$cover_image_name,
                'verify_token'=>$token, 'created_at'=>date( 'Y-m-d h:i:s' )];

           
                // inserting school data to school table...
                $result = $wpdb->insert( 'school', $inset_arr );
                if ( $result ) {
                    $school_id = $wpdb->insert_id;
                    // calling Function to upload school certificates...
                    uploadSchoolCertificates( $school_id, $wpdb, $path, $token, $rand_password );

                } else {
                    throw new Exception( 'School not created due to server error' );
                }
            }

        }

        // return error response...
        catch( Exception $e ) {
            $wpdb->query( 'ROLLBACK' );

            $response = ['status'=>400, 'message'=> $e->getMessage()];
            echo json_encode( $response );
            exit();

        }
    }

    // if a user directly access the page...
    else {
        $response = ['status'=>400, 'message'=>'Unauthorized Access'];

        // return error response...
        echo json_encode( $response );

        exit();
    }
}

// function to upload school certificates...

function uploadSchoolCertificates( $school_id, $wpdb, $path, $token = null, $password = null, $type = null ) {
    $allowedTypes = ['jpeg', 'jpg', 'png', 'pdf'];

    if ( !empty( $_FILES['document']['name'] ) ) {
        try {

            foreach ( $_FILES['document']['size'] as $key=>$size ) {
                $name = $_FILES['document']['name'];

                // get the extension of each certificate...
                $ext = pathinfo( $name[$key], PATHINFO_EXTENSION );


                // if the type of certificate is not found in allowed type...
                if ( !in_array( $ext, $allowedTypes ) ) {
                    throw new Exception( 'Certificates of only jpg,jpeg and png formats are allowed' );
                }

                // if size of any certificate exceeds 2 MB...
                if ( $size>2*1024*1024 ) {
                    throw new Exception( 'Document size should not exceed 2 MB' );
                }

                // generating the name of certificate file...
                $doc_name = microtime().'.'.$ext;
                // echo $doc_name;die;

                // inserting school certificates in school certificate table...
                $insert_school_certificates = ['school_id'=>$school_id, 'document'=>$doc_name, 'created_at'=>time()];
                $result = $wpdb->insert( 'school_certificate', $insert_school_certificates );

                if ( $result ) {
                  
                    // uploading certificate to image folder...
                    if (!move_uploaded_file($_FILES['document']['tmp_name'][$key], $path.'/assets/certificates/'.$doc_name)) {
                        throw new Exception( 'Certificates not uploaded due to error' );
                    }
                } else {
                    throw new Exception( 'Certificates not inserted due to error' );
                }
            }
            if ( $type != 'update' ) {
                // calling sendmail function to send mail...
                if ( sendMail( $token, $school_id, $password ) ) {
                    $response = ['status'=>200, 'message'=>'School Created Successfully'];

                    // commiting the transaction...
                    $wpdb->query( 'COMMIT' );

                    echo json_encode( $response );
                }
            } else {
                $response = ['status'=>Success_Code, 'message'=>'School Updated Successfully'];

                // commiting the transaction...
                $wpdb->query( 'COMMIT' );

                echo json_encode( $response );
                exit;
            }
        } catch( Exception $e ) {
            $response = ['status'=>400, 'message'=> $e->getMessage()];

            // to rollback the transaction...
            $wpdb->query( 'ROLLBACK' );

            // returning error response...
            echo json_encode( $response );
            exit();

        }

    } else {
        $wpdb->query( 'COMMIT' );

        // calling sendmail function to send mail...
        if ( sendMail( $token, $school_id, $password ) ) {
            $response = ['status'=>200, 'message'=>'Mail sent Successfuly.Please check your mail to get verified.'];

            // returning success response...
            echo json_encode( $response );
            exit();
        }
    }
}

// function to send mail to school for login...

function sendMail( $token, $id, $password ) {

    // encoding School id...
    $id = base64_encode( $id );

    // url of the verification page...
    $url = get_home_url().'/index.php/account-verification/?tok='.$token.'&school='.$id.'&type=school';

    // html to render when mail will be sent to user...
    $msg = '<h1>Hello '.$_POST['name'].'\n Welcome To Apply board.</h1><p>'.$url." Please verify your account on clicking the link given below.</p><a class='btn btn-primary' href=".$url."></a>
         <h3>Email :</h3>".$_POST['email'].'\n <h3>Password : '.$password.'</h3>';

    try {
        // sending mail to user...
        $mail_res = wp_mail( $_POST['email'], '<h3>Activate Your Applyboard Account</h3>', $msg );

        // if mail success...
        if ( $mail_res ) {
            return true;
        } else {
            throw new Exception( 'Internal server error' );
        }
    }
    // return error response...
    catch( Exception $e ) {
        $response = ['status'=>400, 'message'=>$e->getMessage()];
        echo json_encode( $response );
    }

}

?>