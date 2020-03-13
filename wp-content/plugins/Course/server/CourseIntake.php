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

            if (empty($_POST['course_id'])) {
                throw new Exception("Course id is required");
            }

            if (empty($_POST['date'])) {
                throw new Exception("Please enter the date of course");
            }

            if (empty($_POST['deadline'])) {
                throw new Exception("Please enter the deadline of the course intake");
            }

            // echo "<pre>";
            // print_r($_POST);
            // die;

            switch ($_POST['val']) {

                case 'addCourseIntake':

                    $course_id = base64_decode($_POST['course_id']);

                    $wpdb->query('START TRANSACTION');

                    foreach ($_POST['date'] as $intake_id => $arr) {

                        if (strtotime($arr['start_date']) >= strtotime($arr['end_date'])) {
                            throw new Exception('Course start date cannot be greater than or equal to end date.');
                        }

                        $insert_arr = ['course_id' => $course_id, 'intake_id' => $intake_id,
                            'start_date' => Date('Y-m-d', strtotime($arr['start_date'])),
                            'end_date' => Date('Y-m-d', strtotime($arr['end_date'])),
                            'deadline' => Date('Y-m-d', strtotime($_POST['deadline'][$intake_id])),
                            'created_at' => Date('Y-m-d h:i:s')];

                        $course_intake_res = $wpdb->insert('course_intake', $insert_arr);
                    }

                    if ($course_intake_res) {
                        $wpdb->query('COMMIT');
                        $response = ['status' => Success_Code, 'message' => 'Course intakes created successfully'];
                    } else {
                        throw new Exception("Course Intakes not created due to internal server error");
                    }
                    break;

                case 'updateCourseIntake':

                    if (empty($_POST['intake_id'])) {
                        throw new Exception("Intake id is required");
                    }
                    $course_intake_id = base64_decode($_POST['intake_id']);

                    foreach ($_POST['date'] as $intake_id => $arr) {

                        if (strtotime($arr['start_date']) >= strtotime($arr['end_date'])) {
                            throw new Exception('Course start date cannot be greater than or equal to end date.');
                        }

                        $update_arr = ['start_date' => Date('Y-m-d', strtotime($arr['start_date'])),
                            'end_date' => Date('Y-m-d', strtotime($arr['end_date'])),
                            'deadline' => Date('Y-m-d', strtotime($_POST['deadline'][$intake_id])),
                            'updated_at' => Date('Y-m-d h:i:s')];

                        $course_intake_res = $wpdb->update('course_intake', $update_arr, ['id' => $course_intake_id]);
                    }

                    if ($course_intake_res) {
                        $response = ['status' => Success_Code, 'message' => 'Course intakes updated successfully'];
                    } else {
                        throw new Exception("Course Intakes not updated due to internal server error");
                    }

                    break;

                default:
                    throw new Exception("No case matches");
                    break;
            }
        } catch (Exception $e) {
            $wpdb->query('ROLLBACK');
            $response = ['status' => Error_Code, 'message' => $e->getMessage()];
        }
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

echo json_encode($response);
