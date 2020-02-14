<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function verifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return School::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    if (verifyUser()) {
        try {
            switch ($_POST['val']) {
                case 'getCourseIntake':

                    if (empty($_POST['course_id'])) {
                        throw new Exception("Course id is required");
                    }

                    $c_id = $_POST['course_id'];
                    $course = $wpdb->get_results("select intake from courses where id=" . $c_id);
                    $intakes = json_decode($course[0]->intake, true);

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

                    break;

                case 'updateCourseIntake':

                    if (empty($_POST['course_id'])) {
                        throw new Exception("Course id is required");
                    }

                    if (empty($_POST['start_date'])) {
                        throw new Exception("Please enter the start date of course");
                    }

                    if (empty($_POST['end_date'])) {
                        throw new Exception("Please enter the end date of course");
                    }

                    foreach ($_POST['start_date'] as $month => $arr) {

                        foreach ($arr as $year => $start_date) {

                            $intake_start_date = $start_date;
                            $intake_end_date = $_POST['end_date'][$month][$year];

                            $ins_arr = ['course_id' => $_POST['course_id'], 'intake_id' => $month,
                                'year' => $year, 'start_date' => Date('Y-m-d',strtotime($intake_start_date)),
                                'end_date' => Date('Y-m-d', strtotime($intake_end_date)), 'created_at' => Date('Y-m-d h:i:s')];

                            $course_intake_res = $wpdb->insert('course_intake', $ins_arr);
                        }
                    }
                    if ($course_intake_res) {
                        $response = ['status' => Success_Code, 'message' => 'Course intakes created successfully'];
                    } else {
                        throw new Exception("Course intakes could not be created due to internal server error");
                    }
                    break;
                default:
                    throw new Exception("No case matches");
                    break;
            }
        } catch (Exception $e) {
            $response = ['status' => Error_Code, 'message' => $e->getMessage()];
        }
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

echo json_encode($response);
