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

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    global $payload;

    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser($payload);
}

$allowedExtensions = ['jpg', 'jpeg', 'png'];
$path = dirname(__DIR__, 1);

if (!empty($_POST['val'])) {
    try {
        // if token is verfied...
        if (verifyUser()) {
            $id = $payload->userId;

            switch ($_POST['val']) {

                // to validate the old password...
                case 'validateOldPassword':

                    // to get the old password...
                    $old_password = $_POST['password'];

                    //query to get the current password...
                    $sql = 'select password from users where id=' . $id;
                    $data = $wpdb->get_results($sql);

                    $current_password = $data[0]->password;

                    // if password does not matches with the current password...
                    if (!password_verify($old_password, $current_password)) {
                        throw new Exception('Password is incorrect');
                    }
                    // if password is correct...
                    else {
                        // creating  a token...
                        $token = md5(rand(10000, 100000000000));

                        // update token in users table...
                        $wpdb->update('users', ['forgot_password_token' => $token], ['id' => $id]);
                        $data = ['token' => $token];

                        // returning the response...
                        $response = ['status' => Success_Code, 'message' => 'Password is correct', 'data' => $data];
                    }
                    break;

                case 'updatePassword':
                    foreach ($_POST as $key => $value) {

                        // validating the form values...
                        if (empty($value)) {
                            throw new Exception($key . ' is required');
                        }
                    }

                    // if length of the password is less than 6 characters...
                    if (strlen($_POST['password']) < 6) {
                        throw new Exception('Password length should be minimum six characters.');
                    }

                    // if password and confirm password are not same...
                    if ($_POST['password'] != $_POST['confirm_password']) {
                        throw new Exception('Password and confirm password should be same');
                    }

                    // creating the hash value of password...
                    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    // get the forgot password token...
                    $token = $_POST['token'];

                    // update password of user...
                    $update = $wpdb->update('users', ['password' => $new_password], ['id' => $id, 'forgot_password_token' => $token, 'role' => '1']);

                    // if password not updated...
                    if (!$update) {
                        throw new Exception('Password not updated due to internal server error');
                    }

                    // if password updated successfully...
                    else {
                        $response = ['status' => Success_Code, 'message' => 'Password Updated Successfully'];
                    }
                    break;

                // case to update the user profile...
                case 'updateProfile':

                    $id = $payload->userId;

                    $require_arr = ['first_name', 'last_name', 'email', 'dob', 'marks', 'pass_number'];
                    foreach ($require_arr as $form_input) {

                        if (!array_key_exists($form_input, $_POST)) {
                            throw new Exception("Please enter your " . $form_input);
                        }
                    }

                    if (empty($_POST['lang_prior'])) {
                        throw new Exception("Please select the language");
                    }

                    if (empty($_POST['exams'])) {
                        throw new Exception("Please select atleast one exam");
                    }

                    if (empty($_POST['nationality'])) {
                        throw new Exception("Please select your nationality");
                    }

                    if (empty($_POST['gender'])) {
                        throw new Exception("Please select your gender");
                    }

                    if (empty($_POST['qualification'])) {
                        throw new Exception("Please select the qualification");
                    }

                    if (empty($_POST['grade_scheme'])) {
                        throw new Exception("Please select the grade scheme");
                    }

                    if (empty($_POST['visa'])) {
                        throw new Exception("Please select the visa");
                    }

                    $email = $_POST['email'];

                    if (!filter_var($email, FILTER_DEFAULT)) {
                        throw new Exception("Invalid email address");
                    }
                    // get the user email...
                    $email = $_POST['email'];

                    $email_exists = $wpdb->get_results("select email from users where email='" . $email . "' && id!=" . $id);

                    $exams = json_encode($_POST['exams']);

                    if (!empty($email_exists)) {
                        throw new Exception("This email already exists.Try another");
                    }

                    $dob = Date('Y-m-d', strtotime($_POST['dob']));

                    if ($_POST['gender'] == "male") {
                        $gender = '1';
                    } else {
                        $gender = '2';
                    }
                    // echo "<pre>";
                    // print_r($_POST);
                    // print_r($_FILES);
                    // die;
                    // if user updates the image...
                    if (!empty($_FILES['img_input']['name'])) {

                        $image_name = $_FILES['img_input']['name'];

                        // get the type of image...
                        $type = pathinfo($image_name, PATHINFO_EXTENSION);

                        $size = $_FILES['img_input']['size'];

                        // if image size exceeds 2 MB...
                        if ($size > 2 * 1024 * 1024) {
                            throw new Exception('Image should not exceed more than 2 MB');
                        }
                        // if image type is not allowed...
                        if (!in_array($type, $allowedExtensions)) {
                            throw new Exception('Only jpg,jpeg and png formats are allowed');
                        }

                        // if oldimage exists...
                        if (!empty($_POST['cur_image'])) {
                            $old_image = $_POST['cur_image'];
                            // deleting the image from folder...
                            if (!unlink($path . '/assets/images/' . $old_image)) {
                                throw new Exception('Image not deleted due to internal server error');
                            }
                        }

                        // generating a new image name using time function...
                        $image_name = microtime() . '.' . $type;

                        // upload image to folder...
                        if (!move_uploaded_file($_FILES['img_input']['tmp_name'], $path . '/assets/images/' . $image_name)) {
                            throw new Exception('File not uploaded');
                        }
                    }

                    // if user not changes the image...
                    else {
                        $image_name = $_POST['cur_image'];
                    }

                    $wpdb->query('START TRANSACTION');

                    $update_arr = ['f_name' => $_POST['first_name'], 'l_name' => $_POST['last_name'],
                        'email' => $_POST['email'], 'dob' => $dob, 'language_prior' => $_POST['lang_prior'],
                        'nationality' => $_POST['nationality'], 'passport_no' => $_POST['pass_number'],
                        'gender' => $gender, 'grade_id' => $_POST['qualification'], 'grade_scheme' => $_POST['grade_scheme'],
                        'score' => $_POST['marks'], 'has_visa' => $_POST['visa'], 'exam' => $exams,
                         'image' => $image_name,'updated_at'=>Date('Y-m-d h:i:s')];

                        // echo "<pre>";
                        // print_r($update_arr);
                        // die;

                    // update query to update profile...
                    $update_stu_res = $wpdb->update('users', $update_arr, ['id' => $id]);

                    if ($update_stu_res) {

                        if (!empty($_FILES['documents']['name'][0])) {

                            // calling function to upload user documents...
                            if (uploadStudentDocuments($wpdb, $id)) {
                                $wpdb->query('COMMIT');
                                $response = ['status' => Success_Code, 'message' => 'Profile Updated Successfully'];
                            }
                        } else {
                            $wpdb->query('COMMIT');
                            $response = ['status' => Success_Code, 'message' => 'Profile Updated Successfully'];
                        }

                    } else {
                        throw new Exception("Profile not updated due to internal server error");
                    }

                    break;

                // if no case matches...
                default:
                    throw new Exception('No match Found.');
                    break;
            }
        }
    }

    // catch the exception...
     catch (Exception $e) {
        $wpdb->query('ROLLBACK');
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
}

// if user directly access this page...
else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}

function uploadStudentDocuments($wpdb, $student_id)
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
        $path = dirname(__DIR__) . "/assets/documents/" . $doc_name;

        // to move document inside the folder...
        if (!move_uploaded_file($_FILES['documents']['tmp_name'][$key], $path)) {
            throw new Exception("Document could not be uploaded in the sever directory.Try again");
        }

        // inserting the documents inside the folder...
        $doc_ins_res = $wpdb->insert('user_documents', ['user_id' => $student_id, 'document' => $doc_name, 'created_at' => Date('Y-m-d h:i:s')]);
    }

    if ($doc_ins_res) {
        return true;
    }
}

// returning json response...
echo json_encode($response);
