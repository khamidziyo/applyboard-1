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

function subAgentVerify()
{
    global $payload;
    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return SubAgent::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {
        // echo "<pre>";
        // print_r($_POST);
        // die;
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

            case 'courseIntakeByAgent':
                if (agentVerify()) {
                    getCourseIntake($wpdb, $course_id);
                }
                break;

            case 'courseIntakeByStudent':
                if (studentVerify()) {
                    getCourseIntake($wpdb, $course_id);
                }

                break;

            // when user clicks on apply button to apply for particular course...
            case 'applyCourseByStudent':
                if (studentVerify()) {

                    $student_id = $payload->userId;

                    if (empty($_POST['intake'][0]['value'])) {
                        throw new Exception("Please select the intake you want");
                    }
                    $course_id = base64_decode($_POST['course']);

                    // query to check whether that school applications are managed by staff or by itself...
                    $application_manage = $wpdb->get_results("select id,staff from school where id
                                        =(select school_id from courses where id=" . $course_id . ")");

                    // to get the staff from school table....
                    $manage_staff = $application_manage[0]->staff;
                    $school_id = $application_manage[0]->id;

                    // if the staff is 1...
                    if ($manage_staff) {

                        // 1 if applications to be manage by staff...
                        $manage_by = '1';
                    }
                    // else it will be manage by school themeself...
                    else {

                        // 0 if application to be manage by school...
                        $manage_by = '0';
                    }

                    $intake = json_encode(explode("/", $_POST['intake'][0]['value']));

                    $stu_exist = $wpdb->get_results("select * from users where id=" . $student_id . " && role='1'");

                    if (empty($stu_exist)) {
                        throw new Exception("Student does not exist.Invalid student id");
                    }

                    $insert_app = ['student_id' => $student_id, 'school_id' => $school_id, 'course_id' => $course_id,
                        'intake' => $intake, 'manage_by' => $manage_by, 'created_at' => Date('Y-m-d h:i:s')];

                    // insert the application record in applications table...
                    $application_res = $wpdb->insert('applications', $insert_app);

                    // if application submitted successfully...
                    if ($application_res) {
                        $response = ['status' => Success_Code, 'message' => 'your application submitted Successfully'];
                    } else {
                        throw new Exception("Application not created due to internal server error");
                    }
                }
                break;

            case 'applyCourseByAgent':
                if (agentVerify()) {
                    // logged in agent id...
                    $id = $payload->userId;

                    applyCourseByAgent($wpdb, $id);
                }
                break;

            case 'applyCourseBySubAgent':
                if (subAgentVerify()) {
                    applyCourseByAgent($wpdb);
                }
                break;

            // if no match found...
            default:
                throw new Exception("No Match Found");
                break;
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

function getCourseIntake($wpdb, $course_id)
{

    $data = $wpdb->get_results("select intake from courses where id=" . $course_id);
    $intakes = json_decode($data[0]->intake, true);
    $intake_months = $wpdb->get_results("select id,name from intakes where status='1' && id in (" . implode(",", $intakes) . ")");

    // get the current month...
    $month = date('m');

    foreach ($intake_months as $key => $obj) {

        if ($obj->id >= $month) {
            $intake_avail[] = ['id' => $obj->id, 'name' => $obj->name];
        } else {
            $intake_avail_next[] = ['id' => $obj->id, 'name' => $obj->name];
        }
    }
    $response = ['status' => Success_Code, 'message' => 'Intake Fetched successfully',
        'intake_avail' => $intake_avail, 'intake_avail_next' => $intake_avail_next];

    // returning the json response...
    echo json_encode($response);
    exit;
}

function applyCourseByAgent($wpdb, $id)
{

    if (empty($_POST['student_id'])) {
        throw new Exception("Student id is required");
    }

    if (empty($_POST['intake'][0]['value'])) {
        throw new Exception("Please select the intake you want");
    }

    $course_id = base64_decode($_POST['course']);
    $student_id = base64_decode($_POST['student_id']);

    // query to check whether that school applications are managed by staff or by itself...
    $application_manage = $wpdb->get_results("select id,staff from school where id
    =(select school_id from courses where id=" . $course_id . ")");

    // to get the staff from school table....
    $manage_staff = $application_manage[0]->staff;
    $school_id = $application_manage[0]->id;

    // if the staff is 1...
    if ($manage_staff) {

        // 1 if applications to be manage by staff...
        $manage_by = '1';
    }
    // else it will be manage by school themeself...
    else {

        // 0 if application to be manage by school...
        $manage_by = '0';
    }

    $intake = json_encode(explode("/", $_POST['intake'][0]['value']));

    $stu_exist = $wpdb->get_results("select * from users where id=" . $student_id . " && role='1'");

    if (empty($stu_exist)) {
        throw new Exception("Student does not exist.Invalid student id");
    }

    $insert_app = ['student_id' => $student_id, 'school_id' => $school_id, 'agent_id' => $id,
        'course_id' => $course_id, 'intake' => $intake, 'manage_by' => $manage_by, 'created_at' => Date('Y-m-d h:i:s')];

    // insert the application record in applications table...
    $application_res = $wpdb->insert('applications', $insert_app);

    // if application submitted successfully...
    if ($application_res) {
        $response = ['status' => Success_Code, 'message' => 'your application submitted Successfully'];
    }
    echo json_encode($response);
    exit;

}

// returning the json response...
echo json_encode($response);
