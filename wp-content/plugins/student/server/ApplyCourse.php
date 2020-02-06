<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

// function to verify user...

function studentVerify()
{
    global $payload;
    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser($payload);
}

function agentVerify()
{
    global $payload;
    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Agent::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {

        // to check whether the course is empty or not...
        if (empty($_POST['course'])) {
            throw new Exception("Course id is required");
        }

        // decoding the course id...
        $course_id = base64_decode($_POST['course']);

        $course_exist = $wpdb->get_results("select * from courses where id=" . $course_id);

        if (empty($course_exist)) {
            throw new Exception("This course does not exist.Invalid course id");
        }

        // to get the school id of particular course to send the notification...
        $school = $wpdb->get_results("select school_id from courses where id=" . $course_id);
        $school_id = $school[0]->school_id;

        switch ($_POST['val']) {

            // when user clicks on apply button to apply for particular course...
            case 'applyCourseByStudent':
                if (studentVerify()) {
                    $id = $payload->userId;

                    $insert_app = ['student_id' => $id, 'school_id' => $school_id, 'course_id' => $course_id, 'created_at' => date('Y-m-d h:i:s')];

                }
                break;

            case 'applyCourseByAgent':
                if (agentVerify()) {
                    $id = $payload->userId;

                    if (empty($_POST['student'])) {
                        throw new Exception("Student id is required");
                    }
                    $student_id = base64_decode($_POST['student']);

                    $stu_exist = $wpdb->get_results("select * from users where id=" . $student_id . " && role='1'");

                    if (empty($stu_exist)) {
                        throw new Exception("Student does not exist.Invalid student id");
                    }

                    $insert_app = ['student_id' => $student_id, 'school_id' => $school_id, 'agent_id' => $id,
                        'course_id' => $course_id, 'created_at' => date('Y-m-d h:i:s')];
                }
                break;

            // if no match found...
            default:
                throw new Exception("No Match Found");
                break;
        }

        // insert the application record in applications table...
        $application_res = $wpdb->insert('applications', $insert_app);

        // if application submitted successfully...
        if ($application_res) {
            $response = ['status' => Success_Code, 'message' => 'your application submitted Successfully'];
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
