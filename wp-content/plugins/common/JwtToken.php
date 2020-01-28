<?php

if(file_exists(dirname( __FILE__ ).'/Jwt.php')){
    include_once  dirname( __FILE__ ).'/Jwt.php';
}

/**
* Get header Authorization
* */

class JwtToken {

    public static function getBearerToken() {
        try {
            $headers = static::getAuthorizationHeader();
            // HEADER: Get the access token from the header
            if ( !empty( $headers ) ) {
                if ( preg_match( '/Bearer\s(\S+)/', $headers, $matches ) ) {
                    $token = $matches[1];
                      return Jwt::decode( $token, Secret_Key, algo );
                 
                }
            }
            return null;
        } catch( Exception $e ) {
            $response = ['status'=>109, 'message'=>$e->getMessage()];
            echo json_encode( $response );
            exit();
        }
    }

          /**
        * get access token from header
        * */

    public static function getAuthorizationHeader() {
        try {
            $headers = null;
            if ( isset( $_SERVER['Authorization'] ) ) {
                $headers = trim( $_SERVER['Authorization'] );
            } else if ( isset( $_SERVER['HTTP_AUTHORIZATION'] ) ) {
                //Nginx or fast CGI
                $headers = trim( $_SERVER['HTTP_AUTHORIZATION'] );

            } elseif ( function_exists( 'apache_request_headers' ) ) {
                $requestHeaders = apache_request_headers();

                // Server-side fix for bug in old Android versions ( a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }else{
                throw new Exception('Authorization missing in the request');
        }
    }
    return $headers;
}
catch(Exception $e){
    $response=['status'=>109,'message'=>$e->getMessage()];
                echo json_encode( $response );
                exit();
            }
        }
    }

  
        ?>