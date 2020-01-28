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

                    // get the user email...
                    $email = $_POST['email'];

                    // validating the user mail...
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception('Invalid email address');
                    }

                    // query to get email...
                    $sql = "select email from users where email='" . $email . "' && id!=" . $id;
                    $user = $wpdb->get_results($sql);

                    // if the email not already exists...
                    if (!empty($user)) {
                        throw new Exception('This email already exists.Try another');
                    }

                    // get old image...
                    $image_sql = 'select image from users where id=' . $id;

                    $old_image = $wpdb->get_results($image_sql);

                    // if user updates the image...
                    if (!empty($_FILES['profile_image']['name'])) {

                        $image_name = $_FILES['profile_image']['name'];

                        // get the type of image...
                        $type = pathinfo($image_name, PATHINFO_EXTENSION);

                        $size = $_FILES['profile_image']['size'];

                        // if image size exceeds 2 MB...
                        if ($size > 2 * 1024 * 1024) {
                            throw new Exception('Image should not exceed more than 2 MB');
                        }
                        // if image type is not allowed...
                        if (!in_array($type, $allowedExtensions)) {
                            throw new Exception('Only jpg,jpeg and png formats are allowed');
                        }

                        // if oldimage exists...
                        if (!empty($old_image[0]->image)) {

                            // deleting the image from folder...
                            if (!unlink($path . '/assets/images/' . $old_image[0]->image)) {
                                throw new Exception('Image not deleted due to internal server error');
                            }
                        }

                        // generating a new image name using time function...
                        $image_name = microtime() . '.' . $type;

                        // upload image to folder...
                        if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $path . '/assets/images/' . $image_name)) {
                            throw new Exception('File not uploaded');
                        }
                    }

                    // if user not changes the image...
                    else {
                        $image_name = $old_image[0]->image;
                    }

                    // update query to update profile...
                    $wpdb->update('users', ['email' => $email, 'image' => $image_name], ['id' => $id]);
                    $response = ['status' => Success_Code, 'message' => 'Profile Updated Successfully'];

                    break;

                // when user updates the data before applying...
                case 'updateUserData':
                    foreach ($_POST as $key => $value) {
                        if (empty($value)) {
                            throw new Exception($key . " is required");
                        }
                    }
                    if (empty($_FILES['image']['name'])) {
                        throw new Exception("Profile image is required");
                    }

                    $image_name = $_FILES['image']['name'];

                    // get the type of image...
                    $type = pathinfo($image_name, PATHINFO_EXTENSION);

                    $size = $_FILES['image']['size'];

                    // if image size exceeds 2 MB...
                    if ($size > 2 * 1024 * 1024) {
                        throw new Exception('Image should not exceed more than 2 MB');
                    }
                    // if image type is not allowed...
                    if (!in_array($type, $allowedExtensions)) {
                        throw new Exception('Only jpg,jpeg and png formats are allowed');
                    }

                    // generating a new image name using time function...
                    $image_name = microtime() . '.' . $type;

                    // upload image to folder...
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $path . '/assets/images/' . $image_name)) {
                        throw new Exception('File not uploaded');
                    }

                    // update array to update the user...
                    $update_user = ['f_name' => $_POST['f_name'], 'l_name' => $_POST['l_name'],
                        'dob' => date('Y-m-d', strtotime($_POST['dob'])), 'passport_no' => $_POST['passport'],
                        'language_prior' => $_POST['lang_prior'], 'gender' => $_POST['gender'],
                        'image' => $image_name, 'updated_at' => date('Y-m-d h:i:s')];

                    // update query to update...
                    $update = $wpdb->update('users', $update_user, ['id' => $id]);

                    // if user profile updated successfully...
                    if ($update) {
                        $response = ['status' => Success_Code,
                            'message' => 'You successfully updated your profile.Now you can apply to any course.'];
                    }

                    // if profile not updated...
                    else {
                        throw new Exception("Profile not update due to internal server error");
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
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
}

// if user directly access this page...
else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}

// returning json response...
echo json_encode($response);
