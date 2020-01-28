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

if(!empty($_GET['term'])){
    try{
        if(verifyUser()){
            $term=$_GET['term'];
            $sql="select name from courses where name like '%".$term."%'";
            $data=$wpdb->get_results($sql);
            $response=['status'=>Success_Code,'data'=>$data];
        }
    }catch(Exception $e){
        $response=['status'=>Error_Code,'message'=>$e->getMessage()];
    }
}else{
    $response=['status'=>Error_Code,'message'=>'Term is missing'];
}
echo json_encode($response);

?>