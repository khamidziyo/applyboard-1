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
    return Agent::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    if (verifyUser()) {
        try {
            switch ($_POST['val']) {
                case 'addStudent':

                    $allowedExtensions = ['jpg', 'jpeg', 'png'];
                    $id = $payload->userId;

                    $req_arr = ['first_name', 'last_name', 'email', 'dob', 'pass_number', 'marks', 'exams'];
                    foreach ($req_arr as $form_inputs) {

                        trim($_POST[$form_inputs]);

                        if (!array_key_exists($form_inputs, $_POST)) {
                            throw new Exception("Please enter your " . $form_inputs);
                        }
                    }

                    if (!isset($_POST['lang_prior'])) {
                        throw new Exception("Please select the language priority");
                    }

                    if (!isset($_POST['nationality'])) {
                        throw new Exception("Please select your nationality");
                    }

                    if (!isset($_POST['gender'])) {
                        throw new Exception("Please select your gender");
                    }

                    if (!isset($_POST['qualification'])) {
                        throw new Exception("Please select your eduaction qualification");
                    }

                    if (!isset($_POST['grade_scheme'])) {
                        throw new Exception("Please select the grading scheme");
                    }

                    if (empty($_FILES['img_input']['name'])) {
                        throw new Exception("Please upload the profile image");
                    }

                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        throw new Exception("Invalid Email address");
                    }
                    $email = $_POST['email'];

                    $check_mail = $wpdb->get_results("select * from users where email='" . $email . "'");

                    if (!empty($check_mail[0])) {
                        throw new Exception("This email already exists.Try another");
                    }

                    $dob = Date('Y-m-d', strtotime($_POST['dob']));

                    if ($_POST['gender'] = "male") {
                        $gender = '1';
                    } elseif ($_POST['gender'] = "female") {
                        $gender = '2';
                    }

                    $exams = json_encode($_POST['exams']);

                    $image = $_FILES['img_input']['name'];

                    $img_type = pathinfo($image, PATHINFO_EXTENSION);

                    if (!in_array($img_type, $allowedExtensions)) {
                        throw new Exception("Only jpg,jpeg and png formats are allowed");
                    }

                    if ($_FILES['img_input']['size'] > 2 * 1024 * 1024) {
                        throw new Exception("Image size should not exceed more than 2 MB");
                    }

                    $image_name = microtime() . '.' . $img_type;
                    $path = dirname(__DIR__) . "/assets/images/" . $image_name;

                    $ins_stu_arr = ['agent_id' => $id, 'f_name' => trim($_POST['first_name']),
                        'l_name' => trim($_POST['last_name']), 'email' => trim($_POST['email']),
                        'dob' => $dob, 'language_prior' => $_POST['lang_prior'], 'nationality' => $_POST['nationality'],
                        'passport_no' => trim($_POST['pass_number']), 'gender' => $gender, 'grade_id' => $_POST['qualification'],
                        'score' => $_POST['marks'], 'exam' => $exam, 'has_visa' => $_POST['visa'],
                        'image' => $image_name, 'created_at' => Date('Y-m-d h:i:s')];

                    // to start the transaction...
                    $wpdb->query('START TRANSACTION');

                    $ins_stu_res = $wpdb->insert('users', $ins_stu_arr);

                    if ($ins_stu_res) {
                        if (!move_uploaded_file($_FILES['img_input']['tmp_name'], $path)) {
                            throw new Exception("Image could not be uploaded in the sever directory.Try again");
                        }

                        // commit the transaction...
                        $wpdb->query('COMMIT');
                        $response = ['status' => Success_Code, 'message' => "Student Created Successfully"];

                    } else {
                        throw new Exception("Student not created due to internal server error");
                    }

                    break;
                default:
                    throw new Exception("No match Found");
                    break;
            }
        } catch (Exception $e) {
            $wpdb->query('ROLLBACK');
            $response = ['status' => Error_Code, 'message' => $e->getMessage()];
        }
    }
} else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is required"];
}
echo json_encode($response);
