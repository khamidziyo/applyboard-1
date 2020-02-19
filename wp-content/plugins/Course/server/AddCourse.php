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

$allowedExtensions = ['jpeg', 'jpg', 'png'];
$path = dirname(__DIR__, 1);
$exam_marks;

if (!empty($_POST)) {

    try {
        if (verifyUser()) {

            $id = $payload->userId;
            // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
            // die;
            $require_arr = ['course_name', 'course_code', 'course_description', 'app_fee', 'int_tution_fee',
                'int_total_fee', 'dom_tution_fee', 'dom_total_fee',
            ];

            foreach ($require_arr as $form_input) {
                if (!array_key_exists($form_input, $_POST)) {
                    throw new Exception("Please enter the " . $form_input);
                }
            }

            if (!isset($_POST['course_type'])) {
                throw new Exception("Please select the course type");
            }

            if (!isset($_POST['category'])) {
                throw new Exception("Please select the course category");
            }

            if (!isset($_POST['language_of_instruction'])) {
                throw new Exception("Please select the language of instruction");
            }

            if (empty($_POST['c_duration']['time_span'])) {
                throw new Exception("Please select the course duration time");
            }

            if (empty($_POST['c_duration']['day_span'])) {
                throw new Exception("Please enter the course duration in days");
            }

            if (empty($_POST['process_time']['time_span'])) {
                throw new Exception("Please select the processing time");
            }

            if (empty($_POST['process_time']['day_span'])) {
                throw new Exception("Please enter the processing in days");
            }

            if ($_POST['int_tution_fee'] > $_POST['int_total_fee']) {
                throw new Exception('International tution fees cannot be greater than total fees');
            }

            if ($_POST['dom_tution_fee'] > $_POST['dom_total_fee']) {
                throw new Exception('Domestic tution fees cannot be greater than total fees');
            }

            // update course ...
            if (!empty($_POST['course_id'])) {
                $c_id = $_POST['course_id'];

                $c_code_data = $wpdb->get_results("select * from courses where id!=" . $c_id . "&& code='" . $_POST['course_code'] . "'");

                if (!empty($c_code_data)) {
                    throw new Exception('The Course with same course code alreday exists.Try another');
                }

                $c_name_data = $wpdb->get_results("select * from courses where id!=" . $c_id . "&& name='" . $_POST['course_name'] . "'");

                if (!empty($c_name_data)) {
                    throw new Exception('The Course with same name alreday exists.Try another');
                }

                $duration = json_encode($_POST['c_duration']);
                $process_time = json_encode($_POST['process_time']);

                if (!empty($_POST['exams'])) {
                    $exam_marks = json_encode($_POST['exams']);
                }

                if (!empty($_FILES['image']['name'])) {

                    //validating course image size for not greater than 2MB...
                    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                        throw new Exception('Course image size should not be more than 2 MB');
                    }

                    //validating profile image type of only allowed types...
                    if (!in_array(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION), $allowedExtensions)) {
                        throw new Exception('Only jpg,jpeg and png formats are allowed');
                    }

                    // generating a new image name using time function...
                    $image_name = microtime() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                    // upload profile image to folder...
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $path . '/assets/images/' . $image_name)) {
                        throw new Exception('Image File not uploaded');
                    } else {
                        // echo $_POST['image'];
                        if (!unlink($path . '/assets/images/' . $_POST['image'])) {
                            throw new Exception('Image File not deleted');
                        }
                    }
                } else {
                    $image_name = $_POST['image'];
                }

                $update_course_arr = ['name' => $_POST['course_name'], 'code' => $_POST['course_code'],
                    'description' => $_POST['course_description'], 'type_id' => $_POST['course_type'], 'category_id' => $_POST['category'],
                    'duration' => $duration, 'application_fee' => $_POST['app_fee'], 'int_tution_fee' => $_POST['int_tution_fee'],
                    'int_total_fee' => $_POST['int_total_fee'], 'dom_tution_fee' => $_POST['dom_tution_fee'],
                    'dom_total_fee' => $_POST['dom_total_fee'], 'internship' => $_POST['internship'],
                    'language_id' => $_POST['language_of_instruction'], 'process_time' => $process_time,
                    'exam_marks' => $exam_marks, 'image' => $image_name, 'updated_at' => date("Y-m-d h:i:s"),
                ];

                $update_res = $wpdb->update('courses', $update_course_arr, ['id' => $_POST['course_id']]);
                if ($update_res) {
                    $response = ['status' => Success_Code, 'message' => 'Course updated successfully'];
                } else {
                    throw new Exception("Course not updated due to internal server error.Try again");
                }
            }

            // insert new course...
            else {
                if (empty($_FILES['image']['name'])) {
                    throw new Exception("Please select the course image");
                }

                $c_code_data = $wpdb->get_results("select * from courses where school_id=" . $id . " && code='" . $_POST['course_code'] . "'");

                if (!empty($c_code_data)) {
                    throw new Exception('The Course with same course code alreday exists.Try another');
                }

                $c_name_data = $wpdb->get_results("select * from courses where school_id=" . $id . "&& name='" . $_POST['course_name'] . "'");

                if (!empty($c_name_data)) {
                    throw new Exception('The Course with same name alreday exists.Try another');
                }

                $duration = json_encode($_POST['c_duration']);
                $process_time = json_encode($_POST['process_time']);

                if (!empty($_POST['exams'])) {
                    $exam_marks = json_encode($_POST['exams']);
                }

                //validating course image size for not greater than 2MB...
                if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                    throw new Exception('Course image size should not be more than 2 MB');
                }

                //validating profile image type of only allowed types...
                if (!in_array(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION), $allowedExtensions)) {
                    throw new Exception('Only jpg,jpeg and png formats are allowed');
                }

                // generating a new image name using time function...
                $image_name = microtime() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                // upload profile image to folder...
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $path . '/assets/images/' . $image_name)) {
                    throw new Exception('Image File not uploaded');
                }

                $insert_course_arr = ['school_id' => $id, 'name' => $_POST['course_name'],
                    'code' => $_POST['course_code'], 'description' => trim($_POST['course_description']),
                    'type_id' => $_POST['course_type'], 'category_id' => $_POST['category'], 'intake' => $intakes,
                    'duration' => $duration, 'application_fee' => $_POST['app_fee'],
                    'int_tution_fee' => $_POST['int_tution_fee'], 'int_total_fee' => $_POST['int_total_fee'],
                    'dom_tution_fee' => $_POST['dom_tution_fee'], 'dom_total_fee' => $_POST['dom_total_fee'],
                    'internship' => $_POST['internship'], 'language_id' => $_POST['language_of_instruction'],
                    'process_time' => $process_time, 'exam_marks' => $exam_marks, 'image' => $image_name,
                ];

                $insert_res = $wpdb->insert('courses', $insert_course_arr);

                if ($insert_res) {
                    $response = ['status' => Success_Code, 'message' => 'Course created successfully'];
                } else {
                    throw new Exception('Course not created due to internal server error');
                }
            }
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
    echo json_encode($response);
    exit();
}
