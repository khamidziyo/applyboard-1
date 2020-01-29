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
    return School::verifyUser($payload);
}

$allowedExtensions = ['jpg', 'jpeg', 'png'];
$allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];

$path = dirname(__DIR__, 1);

if (!empty($_POST['val'])) {
    try {

        // if token is verfied...
        if (verifyUser()) {

            switch ($_POST['val']) {

                // to validate the old password...
                case 'validateOldPassword':

                    // to get the old password...
                    $old_password = $_POST['password'];
                    $id = $payload->userId;

                    //query to get the current password...
                    $sql = 'select password from school where id=' . $id;
                    $data = $wpdb->get_results($sql);

                    $current_password = $data[0]->password;

                    // if password does not matches with the current password...
                    if (md5($old_password) != $current_password) {
                        throw new Exception('Password is incorrect');
                    }
                    // if password is correct...
                    else {
                        // creating  a token...
                        $token = md5(rand(10000, 100000000000));

                        // update token in users table...
                        $wpdb->update('school', ['forgot_password_token' => $token], ['id' => $id]);
                        $data = ['token' => $token];

                        // returning the response...
                        $response = ['status' => Success_Code, 'message' => 'Password is correct', 'data' => $data];
                    }
                    break;

                case 'updatePassword':
                    // die;

                    if (empty($_POST['password'])) {
                        throw new Exception("Please enter your new password");
                    }
                    if (empty($_POST['confirm_password'])) {
                        throw new Exception("Please enter your new password");
                    }
                    if (empty($_POST['token'])) {
                        throw new Exception("Password Token is required");
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
                    $new_password = md5($_POST['password']);

                    $id = $payload->userId;

                    // get the forgot password token...
                    $token = $_POST['token'];

                    // update password of user...
                    $update = $wpdb->update('school', ['password' => $new_password], ['id' => $id, 'forgot_password_token' => $token]);

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

                    $school_id = $payload->userId;
                    $accomodation = 0;
                    $living_cost = 0;
                    $work_study = 0;
                    $offer_letter = 0;

                    if (empty($_POST['school_name'])) {
                        throw new Exception("Please enter your school name");
                    }

                    if (empty($_POST['email'])) {
                        throw new Exception("Please enter your school email");
                    }

                    if (empty($_POST['description'])) {
                        throw new Exception("Please give a short dexcription of your school");
                    }

                    if (empty($_POST['address'])) {
                        throw new Exception("Please enter your school address");
                    }

                    if (empty($_POST['number'])) {
                        throw new Exception("Please enter your school contact number");
                    }

                    if (empty($_POST['pin_code'])) {
                        throw new Exception("Please enter your school pincode");
                    }

                    if (empty($_POST['country'])) {
                        throw new Exception("Please select the country in which your school is");
                    }

                    if (empty($_POST['state'])) {
                        throw new Exception("Please select the state in which your school is");
                    }

                    if (empty($_POST['city'])) {
                        throw new Exception("Please select the city in which your school is");
                    }

                    if (empty($_POST['school_type'])) {
                        throw new Exception("Please select the type of your school");
                    }

                    $email = $_POST['email'];

                    // validating the user mail...
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception('Invalid email address');
                    }

                    // query to get email...
                    $sql = "select email from school where email='" . $email . "' && id!=" . $school_id;
                    $school = $wpdb->get_results($sql);

                    // if the email not already exists...
                    if (!empty($school)) {
                        throw new Exception('This email already exists.Try another');
                    }

                    if (!empty($_POST['accomodation'])) {
                        $accomodation = $_POST['accomodation'];
                        if (empty($_POST['living_cost'])) {
                            throw new Exception("Please enter the living cost");
                        }

                        $living_cost = $_POST['living_cost'];
                    }

                    if (!empty($_POST['work_study'])) {
                        $work_study = $_POST['work_study'];
                    }

                    if (!empty($_POST['offer_leter'])) {
                        $offer_letter = $_POST['offer_leter'];
                    }

                    // get the current profile image...
                    $image_sql = 'select profile_image from school where id=' . $school_id;

                    $old_image = $wpdb->get_results($image_sql);
                    $profile_image_name = $old_image[0]->profile_image;

                    // if user updates the image...
                    if (!empty($_FILES['profile_image_input']['name'])) {

                        $image_name = $_FILES['profile_image_input']['name'];

                        // get the type of image...
                        $type = pathinfo($image_name, PATHINFO_EXTENSION);

                        $size = $_FILES['profile_image_input']['size'];

                        // if image size exceeds 2 MB...
                        if ($size > 2 * 1024 * 1024) {
                            throw new Exception('Image should not exceed more than 2 MB');
                        }

                        // if image type is not allowed...
                        if (!in_array($type, $allowedExtensions)) {
                            throw new Exception('Only jpg,jpeg and png formats are allowed');
                        }

                        // if oldimage exists...
                        if (!empty($profile_image_name)) {

                            // deleting the image from folder...
                            if (!unlink($path . '/assets/images/' . $profile_image_name)) {
                                throw new Exception('Profile Image not deleted due to internal server error');
                            }
                        }

                        // generating a new image name using time function...
                        $profile_image_name = microtime() . '.' . $type;

                        // upload image to folder...
                        if (!move_uploaded_file($_FILES['profile_image_input']['tmp_name'], $path . '/assets/images/' . $profile_image_name)) {
                            throw new Exception('Profile image not uploaded to server');
                        }
                    }

                    // get the current cover image...
                    $image_sql = 'select cover_image from school where id=' . $school_id;

                    $old_image = $wpdb->get_results($image_sql);
                    $cover_image_name = $old_image[0]->cover_image;

                    if (!empty($_FILES['cover_image_input']['name'])) {

                        $image_name = $_FILES['cover_image_input']['name'];

                        // get the type of image...
                        $type = pathinfo($image_name, PATHINFO_EXTENSION);

                        $size = $_FILES['cover_image_input']['size'];

                        // if image size exceeds 2 MB...
                        if ($size > 2 * 1024 * 1024) {
                            throw new Exception('Cover Image should not exceed more than 2 MB');
                        }

                        // if image type is not allowed...
                        if (!in_array($type, $allowedExtensions)) {
                            throw new Exception('Only jpg,jpeg and png formats are allowed');
                        }

                        // if oldimage exists...
                        if (!empty($cover_image_name)) {

                            // deleting the image from folder...
                            if (!unlink($path . '/assets/images/' . $cover_image_name)) {
                                throw new Exception('Cover Image not deleted due to internal server error');
                            }
                        }

                        // generating a new image name using time function...
                        $cover_image_name = microtime() . '.' . $type;

                        // upload image to folder...
                        if (!move_uploaded_file($_FILES['cover_image_input']['tmp_name'], $path . '/assets/images/' . $cover_image_name)) {
                            throw new Exception('Cover Image not uploaded to server');
                        }
                    }

                    // to start the transaction...
                    $wpdb->query('START TRANSACTION');

                    $school_update_arr = [
                        'name' => $_POST['school_name'], 'email' => $_POST['email'], 'address' => $_POST['address'],
                        'number' => $_POST['number'], 'description' => trim($_POST['description']),
                        'countries_id' => $_POST['country'], 'state_id' => $_POST['state'],
                        'city_id' => $_POST['city'], 'type' => $_POST['school_type'], 'postal_code' => $_POST['pin_code'],
                        'accomodation' => $accomodation, 'living_cost' => $living_cost, 'work_studying' => $work_study, 'offer_letter' => $offer_letter,
                        'profile_image' => $profile_image_name, 'cover_image' => $cover_image_name,
                    ];

                    // update query to update profile...
                    $wpdb->update('school', $school_update_arr, ['id' => $school_id]);

                    if (!empty($_FILES['certificates'])) {

                        foreach ($_FILES['certificates']['size'] as $key => $size) {
                            $name = $_FILES['certificates']['name'];

                            // get the extension of each certificate...
                            $ext = pathinfo($name[$key], PATHINFO_EXTENSION);

                            // if the type of certificate is not found in allowed type...
                            if (!in_array($ext, $allowedTypes)) {
                                throw new Exception('Certificates of only jpg,jpeg and png formats are allowed');
                            }

                            // if size of any certificate exceeds 2 MB...
                            if ($size > 2 * 1024 * 1024) {
                                throw new Exception('Document size should not exceed 2 MB');
                            }

                            // generating the name of certificate file...
                            $doc_name = microtime() . '.' . $ext;
                            // echo $doc_name;die;

                            // transactions starts here

                            // inserting school certificates in school certificate table...
                            $insert_school_certificates = ['school_id' => $school_id, 'document' => $doc_name, 'created_at' => time()];
                            $result = $wpdb->insert('school_certificate', $insert_school_certificates);

                            if ($result) {

                                // uploading certificate to image folder...
                                if (!move_uploaded_file($_FILES['certificates']['tmp_name'][$key], $path . '/assets/certificates/' . $doc_name)) {
                                    throw new Exception('Certificates not uploaded due to error');
                                }

                                // commiting the transaction...
                                $wpdb->query('COMMIT');
                                $response = ['status' => Success_Code, 'message' => "Profile Update Successfully"];

                            } else {
                                throw new Exception('Certificates not inserted due to error');
                            }
                        }
                    }

                    // commiting the transaction...
                    $wpdb->query('COMMIT');
                    $response = ['status' => Success_Code, 'message' => "Profile Update Successfully"];

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
        // to rollback the transaction...
        $wpdb->query('ROLLBACK');

        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
}

// if user directly access this page...
else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}

// returning json response...
echo json_encode($response);
