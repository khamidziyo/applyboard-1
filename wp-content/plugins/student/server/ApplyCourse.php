<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

// function to verify user...

function verifyUser()
{
    global $payload;
    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {
        if (verifyUser()) {
            switch ($_POST['val']) {

                // when user clicks on apply button to apply for particular course...
                case 'applyCourse':
                    $id=$payload->userId;

                    $user_data=$wpdb->get_results("select f_name,l_name,dob,language_prior,passport_no,gender,
                    image from users where id=".$id);

                    $user_arr=json_decode(json_encode($user_data[0]), true); 


                    $user_languages=$wpdb->get_results("select id,name from language");


                    if(in_array('',$user_arr)){
                        $response=['status'=>201,
                        'message'=>'You need to complete your profile before applying to any course',
                        'languages'=>$user_languages
                    ];
                        echo json_encode($response);
                        exit;
                    }

                    // echo "work done";die;
                   
                    // to check whether the course is empty or not...
                    if(empty($_POST['course'])){
                        throw new Exception("Course id is required");
                    }

                    // decoding the course id...
                    $course_id=base64_decode($_POST['course']);

                    // to get the school id of particular course to send the notification...
                    $school=$wpdb->get_results("select school_id from courses where id=".$course_id);
                    $school_id=$school[0]->school_id;

                    $insert_app=['user_id'=>$id,'school_id'=>$school_id,'course_id'=>$course_id,'created_at'=>date('Y-m-d h:i:s')];
                   
                   // insert the application record in applications table...
                    $application_res=$wpdb->insert('applications',$insert_app);
                    
                    // if application submitted successfully...
                    if($application_res){
                        $response=['status'=>Success_Code,'message'=>'your application submitted Successfully'];
                    }
                    break;

                    // if no match found...
                default:
                    throw new Exception("No Match Found");
                    break;
            }
        }

        // catch the exception...
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
}

// if user directly access this page...
else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}

// returning the json response...
echo json_encode($response);
