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

if(!empty($_GET)){

    try{

        // if token is verfied...
        if(verifyUser()){

        switch($_GET['val']){

            // case to get the modal data...
            case 'getModalData':

                // sql to check the eligibility ...
                $user_eligibile_sql="select is_eligible from users where id=".$payload->userId;
                $user_eligibile_data=$wpdb->get_results($user_eligibile_sql);

                // if user has already filled the eligibilty data
                if($user_eligibile_data[0]->is_eligible){

                    // response is eligible and no modal will be shown...
                    $response=['status'=>Success_Code,'message'=>"User already eligible",'eligible'=>'1'];
                    
                    // encoding json response...
                    echo json_encode($response);
                    exit;
                }
                // sql to get all the countries...
                $country_sql="select id,name from countries";
                $country_data=$wpdb->get_results($country_sql);

                // sql to get all the grades...
                $grade_scheme_sql="select id,grade_scheme from grade where status='1'";
                $grade_data=$wpdb->get_results($grade_scheme_sql);
 
                // sql to get all the exams...
                $exam_sql="select id,name from exams where status='1'";
                $exam_data=$wpdb->get_results($exam_sql);

                // sql to get all the categories...
                $category_sql="select id,name from category where status='1'";
                $category_data=$wpdb->get_results($category_sql);

                // storing all data in an array...
                $response=['status'=>Success_Code,'eligible'=>0,'cntry_data'=>$country_data,
                'grade'=>$grade_data,'exams'=>$exam_data,'course_category'=>$category_data];

            break;

            // if no match found...
            default:
            throw new Exception("No match Found");
        break;
        }
    }
    }
    
    // catch the exception...
    catch(Exception $e){
        $response=['status'=>Error_Code,'message'=>$e->getMessage()];
    }
}

// if user directly access this page...
else{
    $response=['status'=>Error_Code,'message'=>'Unauthorized Access'];
}

// returning json response...
echo json_encode($response);
?>