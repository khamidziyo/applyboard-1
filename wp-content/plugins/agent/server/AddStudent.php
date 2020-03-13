<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function agentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Agent::verifyUser($payload);
}

function subAgentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return SubAgent::verifyUser($payload);
}

if (!empty($_POST['val'])) {

    switch ($_POST['val']) {

        case 'addStudentByAgent':

            if (agentVerifyUser()) {
                $id = $payload->userId;
                createStudent($wpdb, $id);
            }
            break;

        case 'addStudentBySubAgent':
            if (subAgentVerifyUser()) {
                $id = $payload->userId;
                // echo $id;die;
                createStudent($wpdb, $id);
            }
            break;

        default:
            throw new Exception("No match Found");
            break;
    }

} else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is required"];
}

// function to add the student by agent or sub agent......
function createStudent($wpdb, $id)
{
    try {
        
        // allowed image types... 
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        // require form paramters...
        $req_arr = ['first_name', 'last_name', 'email', 'dob', 'pass_number', 'marks'];
        foreach ($req_arr as $form_inputs) {

            trim($_POST[$form_inputs]);

            // if any form input is missing then throw error...
            if (!array_key_exists($form_inputs, $_POST)) {
                throw new Exception("Please enter your " . $form_inputs);
            }
        }
        
        //if exams are empty when a new student is created...
        if (!isset($_POST['exams'])) {
            throw new Exception("Please select the exam you appeared for");
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

        if (empty($_POST['student_id'])) {
            if (empty($_FILES['img_input']['name'])) {
                throw new Exception("Please upload the profile image");
            }
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid Email address");
        }

        $email = $_POST['email'];

        // when a new student is created...
        if (empty($_POST['student_id'])) {

            // validation for image when a new student is added...
            if (empty($_FILES['img_input']['name'])){
                throw new Exception("Please upload the profile image");
            }
            // check whether a email exists ...
            $check_mail = $wpdb->get_results("select * from users where email='" . $email . "'");

            // if email already exists...
            if (!empty($check_mail[0])) {
                throw new Exception("This email already exists.Try another");
            }
        }

        // when edit the student...
        else {
            // decode the student id...
            $student_id = base64_decode($_POST['student_id']);

            // check whether a new email exists excluding student id...
            $check_mail = $wpdb->get_results("select * from users where email='" . $email . "' && id!=" . $student_id);

            // if email already exists...
            if (!empty($check_mail[0])) {
                throw new Exception("This email already exists.Try another");
            }
        }

        // converting the date of birth to Y-m-d...
        $dob = Date('Y-m-d', strtotime($_POST['dob']));

        if ($_POST['gender'] = "male") {
            $gender = '1';
        } elseif ($_POST['gender'] = "female") {
            $gender = '2';
        }

        // decode the json to store the exam...
        $exams = json_encode($_POST['exams']);

        // echo "<pre>";
        // print_r($_POST);
        // print_r($_FILES);
        // die;

        if (!empty($_FILES['img_input']['name'])) {
            $image = $_FILES['img_input']['name'];

            // get the image type...
            $img_type = pathinfo($image, PATHINFO_EXTENSION);

            // if image type not found in the allowed array...
            if (!in_array($img_type, $allowedExtensions)) {
                throw new Exception("Only jpg,jpeg and png formats are allowed");
            }

            // if image size exceeds more than 2 MB..
            if ($_FILES['img_input']['size'] > 2 * 1024 * 1024) {
                throw new Exception("Image size should not exceed more than 2 MB");
            }

            // creating a new name for the image...
            $image_name = microtime() . '_' . $id . '.' . $img_type;

            // storage path of image...
            $path = dirname(__DIR__,1) . "/student/assets/images/";

            // if the student uploads a new image on edit time then delete the previous image
            //  from folder...

            if (!empty($_POST['cur_image'])) {
                // echo $path . $_POST['cur_image'];
                // die;
                if (!unlink($path . $_POST['cur_image'])) {
                    throw new Exception("Previous image could not be deleted from server");
                }
            }
        } else {

            // else the image is same...
            $image_name = $_POST['cur_image'];
        }

        // to start the transaction...
        $wpdb->query('START TRANSACTION');

        // if student id is in the request...
        if (!empty($_POST['student_id'])) {
            $student_id = base64_decode($_POST['student_id']);

            // update the student...
            $update_res_arr = ['agent_id' => $id, 'f_name' => trim($_POST['first_name']),
                'l_name' => trim($_POST['last_name']), 'email' => trim($_POST['email']),
                'dob' => $dob, 'language_prior' => $_POST['lang_prior'], 'nationality' => $_POST['nationality'],
                'passport_no' => trim($_POST['pass_number']), 'gender' => $gender, 'grade_id' => $_POST['qualification'],
                'grade_scheme' => $_POST['grade_scheme'], 'score' => $_POST['marks'], 'exam' => $exams, 'has_visa' => $_POST['visa'],
                'image' => $image_name, 'updated_at' => Date('Y-m-d h:i:s')];

                // update query...
            $update_stu_res = $wpdb->update('users', $update_res_arr, ['id' => $student_id]);

            // if student updated successfully...
            if ($update_stu_res) {

                // if a student uploads the new image...
                if (!empty($_FILES['img_input']['name'])) {

                    // moving image to folder...
                    if (!move_uploaded_file($_FILES['img_input']['tmp_name'], $path.$image_name)) {
                        throw new Exception("Image could not be uploaded in the sever directory.Try again");
                    }
                }

                // if student uploads the documents...
                if (!empty($_FILES['documents']['name'][0])) {

                    // calling function to upload student documents...
                    if (uploadStudentDocuments($student_id, $wpdb)) {
                        // commit the transaction...
                        $wpdb->query('COMMIT');
                        $response = ['status' => Success_Code, 'message' => "Student Updated Successfully"];
                    }
                } else {

                    // commit the transaction...
                    $wpdb->query('COMMIT');
                    $response = ['status' => Success_Code, 'message' => "Student Updated Successfully"];
                }

            } else {
                throw new Exception("Student not updated due to internal server error");
            }
        } else {

            // if a student is newly created...
            $ins_stu_arr = ['agent_id' => $id, 'f_name' => trim($_POST['first_name']),
                'l_name' => trim($_POST['last_name']), 'email' => trim($_POST['email']),
                'dob' => $dob, 'language_prior' => $_POST['lang_prior'], 'nationality' => $_POST['nationality'],
                'passport_no' => trim($_POST['pass_number']), 'gender' => $gender, 'grade_id' => $_POST['qualification'],
                'grade_scheme' => $_POST['grade_scheme'], 'score' => $_POST['marks'], 'exam' => $exams, 'has_visa' => $_POST['visa'],
                'image' => $image_name, 'created_at' => Date('Y-m-d h:i:s')];

                // insert query to add new student...
            $ins_stu_res = $wpdb->insert('users', $ins_stu_arr);

            // if student inserted successfully...
            if ($ins_stu_res) {

                // to upload the image inside a folder...
                if (!move_uploaded_file($_FILES['img_input']['tmp_name'], $path.$image_name)) {
                    throw new Exception("Image could not be uploaded in the sever directory.Try again");
                }

                // get the id of newly inserted student...
                $student_id = $wpdb->insert_id;

                // if student wants to upload the documents also...
                if (!empty($_FILES['documents']['name'][0])) {

                    // calling function to upload student documents...
                    if (uploadStudentDocuments($student_id, $wpdb)) {
                        // commit the transaction...
                        $wpdb->query('COMMIT');
                        $response = ['status' => Success_Code, 'message' => "Student Created Successfully"];
                    }
                } else {

                    // commit the transaction...
                    $wpdb->query('COMMIT');
                    $response = ['status' => Success_Code, 'message' => "Student Created Successfully"];
                }

            } else {
                throw new Exception("Student not created due to internal server error");
            }
        }

    } catch (Exception $e) {
        $wpdb->query('ROLLBACK');
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
    echo json_encode($response);
    exit;
}

// function to upload documents...
function uploadStudentDocuments($id, $wpdb)
{

    // allowed document type...
    $allowedDocExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

    $docs = $_FILES['documents']['name'];

    // llop to get each document name size and type...
    foreach ($docs as $key => $doc_name) {
        $doc_type = pathinfo($doc_name, PATHINFO_EXTENSION);

        if (!in_array($doc_type, $allowedDocExtensions)) {
            throw new Exception("Only jpg,jpeg,png and pdf formats are allowed");
        }

        if ($_FILES['documents']['size'][$key] > 2 * 1024 * 1024) {
            throw new Exception("Document size should not exceed more than 2 MB");
        }

        // creating a new name for the document...
        $doc_name = microtime() . '.' . $doc_type;
        $path = dirname(__DIR__,1) . "/student/assets/documents/" . $doc_name;

        // to move document inside the folder...
        if (!move_uploaded_file($_FILES['documents']['tmp_name'][$key], $path)) {
            throw new Exception("Document could not be uploaded in the sever directory.Try again");
        }

        // inserting the documents inside the folder...
        $wpdb->insert('user_documents', ['user_id' => $id, 'document' => $doc_name, 'created_at' => Date('Y-m-d h:i:s')]);
    }
// if all done return true...
    return true;
}
