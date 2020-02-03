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

if ( !empty( $_GET['val'] ) ) {

    try{
        switch( $_GET['val'] ) {
            case 'profile':
            if ( verifyUser() ) {
                $id = $payload->userId;
                $sql = 'select email,image,status from users where id='.$id;
                $data = $wpdb->get_results( $sql );
                $response = ['status'=>Success_Code, 'message'=>'Profile fetched successfully', 'data'=>$data[0]];
            }
            break;
    
            case 'oldPassword':
                if ( verifyUser() ) {
                    if(!empty($_GET['password'])){

                        // get the user id from payload...
                        $id = $payload->userId;
                        $password=$_GET['password'];

                        // matching the password...
                        $sql = 'select password from users where id='.$id;
                        $user = $wpdb->get_results( $sql );

                        // if password matches in database...
                        if(password_verify($password,$user[0]->password)){

                            // creating  a token... 
                            $token = md5(rand(10000, 100000000000));

                            // update token in users table...
                            $wpdb->update('users',['forgot_password_token'=>$token],['id'=>$id]);
                            $data=['token'=>$token,'id'=>$id];

                            // returning the response...
                            $response=['status'=>Success_Code,'message'=>'Password is correct','data'=>$data];
                        }else{
                            throw new Exception("Password is incorrect.Please try again");
                        }
                    }else{
                        throw new Exception("Password is required");
                    }
                }
            break;
        }

        // catch the exception...
    }catch(Exception $e){
        $response=['status'=>Error_Code,'message'=>$e->getMessage()];
    }

    // returning json response...
    echo json_encode( $response );

}

?>