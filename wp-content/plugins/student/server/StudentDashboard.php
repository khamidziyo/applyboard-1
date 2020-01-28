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
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser( $payload );
}

if ( !empty( $_POST ) ) {
    try {

        // if token is verfied...
        if ( verifyUser() ) {

            switch( $_POST['val'] ) {

                // if user searches for a program with school...
                case 'searchProgram':
                $program = $_POST['program'];
                $sch_name_loc = $_POST['sch_name'];

                $sql = "select s.id as s_id,s.name as s_name,c.id as c_id,c.image,c.name from school as s join 
                courses as c on c.school_id=s.id where c.name ='".$program."' && s.name='".$sch_name_loc."'";

                $data = $wpdb->get_results( $sql );
                $response = ['status'=>Success_Code, 'data'=>$data[0]];
                break;

                // if no case matches...
                default:
                throw new Exception( 'No match Found' );
                break;
            }
        }
    } catch( Exception $e ) {
        $response = ['status'=>Error_Code, 'message'=>$e->getMessage()];
    }
} else {
    $response = ['status'=>Error_Code, 'message'=>'Unauthorized Access'];
}
echo json_encode( $response );
?>