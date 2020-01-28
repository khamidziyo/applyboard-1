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
    global $payload;

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser( $payload );
}

if ( !empty( $_POST ) ) {
    $grade;
    $score;
    $visa;
    $exam;

    try {

        // if token is verfied...
        if ( verifyUser() ) {

            // if nationality is empty then throw error...
            if ( empty( $_POST['nationality'] ) ) {
                throw new Exception( 'Please select your nationality' );
            }

            // if class is not empty...
            if ( !empty( $_POST['grade'] ) ) {
                $grade = $_POST['grade'];
            }

            // if marks are not empty...
            if ( !empty( $_POST['average'] ) ) {
                $score = $_POST['average'];
            }

            // if visa is not empty...
            if ( !empty( $_POST['visa'] ) ) {
                $visa = $_POST['visa'];
            }

            // if exam is not empty...
            if ( !empty( $_POST['exams'] ) ) {

                // encoding exam array to store in database...
                $exam = json_encode( $_POST['exams'] );
            }

            $eligible_arr = ['nationality'=>$_POST['nationality'], 'grade_id'=>$grade, 'score'=>$score,
            'has_visa'=>$visa, 'exam'=>$exam,'is_eligible'=>'1'];

            // starting the transaction...
            $wpdb->query( 'START TRANSACTION' );

            // updating the user table...
            $update_user = $wpdb->update( 'users', $eligible_arr, ['id'=>$payload->userId] );

            // if update fails...
            if(!$update_user){
                throw new Exception("User not updated due to internal server error");
            }
            if ( !empty( $_POST['course'] )) {
                foreach($_POST['course'] as $category=>$id){

                    // inserting the user interested courses in user interest table...
                    if(!$wpdb->insert('user_interest',['user_id'=>$payload->userId,'category_id'=>$id])){
                        throw new Exception("Course interest not inserted due to internal server error");
                    }
                }
            }
            
            // if all is good... 
            $response=['status'=>Success_Code,'message'=>"You can now apply to courses.You want."];
            
            // commit the transaction...
            $wpdb->query( 'COMMIT' );
  
        }
    } 
    
    // catch the exception on error...
    catch( Exception $e ) {
        $wpdb->query( 'ROLLBACK' );

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