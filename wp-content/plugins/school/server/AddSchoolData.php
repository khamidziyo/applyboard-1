<?php

$error = '';

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function adminVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Admin::verifyUser($payload);
}

if (!empty($_POST['val'])) {

    try {

        switch ($_POST['val']) {

            // if admin adds the school...
            case 'addSchoolByAdmin':
                if (adminVerifyUser()) {

                    $id = $payload->userId;
                    addUpdateSchool($wpdb, $id);
                }
                break;

            // if admin updates the school...
            case 'updateSchoolByAdmin':

                if (adminVerifyUser()) {

                    $id = $payload->userId;

                    if (empty($_POST['school_id'])) {
                        throw new Exception("School id is required");
                    }
                    $school_id = base64_decode($_POST['school_id']);

                    addUpdateSchool($wpdb, $id, $school_id);
                }
                break;
        }
    }

    // return error response...
     catch (Exception $e) {
        $wpdb->query('ROLLBACK');

        $response = ['status' => 400, 'message' => $e->getMessage()];
        echo json_encode($response);
        exit();

    }
}

// if a user directly access the page...
else {
    $response = ['status' => 400, 'message' => 'Unauthorized Access.Value is required'];

    // return error response...
    echo json_encode($response);

    exit();
}

// function to add and update the school...
function addUpdateSchool($wpdb, $id, $school_id = null)
{
    $path = dirname(__DIR__, 1);
    $accomodation = 0;
    $work_studying = 0;
    $offer_letter = 0;
    $living_cost;


    $require_arr = ['name', 'email', 'address', 'number', 'description', 'pin_code'];
    $require_select_arr = ['country', 'state', 'city', 'school_type', 'description', 'pin_code'];

    // validation for form inputs...
    foreach ($require_arr as $form_input) {

        // if post data keys does not exist in the require array defined...
        if (!array_key_exists($form_input, $_POST)) {
            throw new Exception($form_input . " is missing in the request");
        }
        // if any post data value is empty...
        elseif (empty($_POST[$form_input])) {
            throw new Exception("Please enter the " . $form_input);
        }
    }

    // validation for dropdown data...
    foreach ($require_select_arr as $form_input) {

        // if post data keys does not exist in the require array defined...
        if (!array_key_exists($form_input, $_POST)) {
            throw new Exception($form_input . " is missing in the request");
        }
        // if any post data value is empty...
        elseif (empty($_POST[$form_input])) {
            throw new Exception("Please select the " . $form_input);
        }
    }

    // if phone number length is less than 10 digits...
    if (strlen($_POST['number']) < 10) {
        throw new Exception('Invalid mobile number');
    }

    // if the school provides accomodation facility...
    if (isset($_POST['accomodation'])) {
        $accomodation = 1;

        // if living cost data is empty if accomodation is selected...
        if (empty($_POST['living_cost'])) {
            throw new Exception("Please enter livng cost for accomodation");
        }

        $living_cost = $_POST['living_cost'];
    }

    // if school permits work while studying...
    if (isset($_POST['work_studying'])) {
        $work_studying = 1;
    }

    // if school provides conditional offer letter...
    if (isset($_POST['offer_letter'])) {
        $offer_letter = 1;
    }

    // if email is not a valid email...
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address");
    }

    $email = $_POST['email'];

    // to update the school data...
    if ($school_id != null) {

        // to check the email if already exist when updates the school...
        $email_exist = $wpdb->get_results("select email from school where email='" . $email . "' && id!=" . $school_id);

        // if email exists...
        if (!empty($email_exist)) {
            throw new Exception("The school with same email already exist.Try another.");
        }

        // if admin selects the new school profile image...
        if (!empty($_FILES['profile_image']['name'])) {

            // calling function so as to validate and upload into a folder...
            $profile_image = uploadImage($_FILES['profile_image']);

            if (!unlink($path . '/assets/images/' . $_POST['previous_profile_image'])) {
                throw new Exception('Unable to delete school profile image due to server error');
            }
        } else {
            $profile_image = $_POST['previous_profile_image'];
        }

        if (!empty($_FILES['cover_image']['name'])) {
            $cover_image = uploadImage($_FILES['cover_image']);

            if (!unlink($path . '/assets/images/' . $_POST['previous_cover_image'])) {
                throw new Exception('Unable to delete school cover image due to server error');
            }
        } else {
            $cover_image = $_POST['previous_cover_image'];
        }
        // to start the transaction...
        $wpdb->query('START TRANSACTION');

        $update_arr = ['user_id' => $id, 'name' => $_POST['name'], 'email' => $_POST['email'],
            'address' => $_POST['address'], 'number' => $_POST['number'], 'description' => $_POST['description'],
            'countries_id' => $_POST['country'], 'state_id' => $_POST['state'], 'city_id' => $_POST['city'],
            'type' => $_POST['school_type'], 'postal_code' => $_POST['pin_code'], 'accomodation' => $accomodation,
            'living_cost' => $living_cost, 'work_studying' => $work_studying, 'offer_letter' => $offer_letter,
            'profile_image' => $profile_image, 'cover_image' => $cover_image, 'updated_at' => date('Y-m-d h:i:s')];

        // updating school data to school table...
        $update_school_res = $wpdb->update('school', $update_arr, ['id' => $school_id]);

        if ($update_school_res) {

            if (!empty($_FILES['document']['name'][0])) {
                if (uploadSchoolCertificates($school_id, $wpdb, $path)) {

                    // commiting the transaction...
                    $wpdb->query('COMMIT');

                    $response = ['status' => Success_Code, 'message' => 'School updated Successfully'];
                }
            } else {
                $response = ['status' => Success_Code, 'message' => 'School updated Successfully'];
            }
        } else {
            $response = ['status' => Error_Code, 'message' => 'School not updated due to internal server error.'];
        }

    }

    // to insert the new school...
    else {
        $email_exist = $wpdb->get_results("select email from school where email='" . $email . "'");
        if (!empty($email_exist)) {
            throw new Exception("The school with same email already exist.Try another.");
        }

        if (empty($_FILES['profile_image']['name'])) {
            throw new Exception("Please upload the school profile image");
        }
        $profile_image = uploadImage($_FILES['profile_image']);

        if (empty($_FILES['cover_image']['name'])) {
            throw new Exception("Please upload the school cover image");
        }
        $cover_image = uploadImage($_FILES['cover_image']);

        // creating new password...
        $rand_password = rand();
        $password = md5($rand_password);

        //creating token to verify...
        $token = base64_encode(rand(1000, 1000000));

        // to start the transaction...
        $wpdb->query('START TRANSACTION');

        $insert_arr = ['user_id' => $id, 'name' => $_POST['name'], 'email' => $_POST['email'], 'password' => $password, 'address' => $_POST['address'],
            'number' => $_POST['number'], 'description' => $_POST['description'], 'countries_id' => $_POST['country'], 'state_id' => $_POST['state'],
            'city_id' => $_POST['city'], 'type' => $_POST['school_type'], 'postal_code' => $_POST['pin_code'],
            'accomodation' => $accomodation, 'living_cost' => $living_cost,
            'work_studying' => $work_studying, 'offer_letter' => $offer_letter, 'profile_image' => $profile_image,
            'cover_image' => $cover_image,
            'verify_token' => $token, 'created_at' => date('Y-m-d h:i:s')];
        // echo "<pre>";
        // print_r($insert_arr);
        // print_r($_FILES);
        // die;
        $insert_school_res = $wpdb->insert('school', $insert_arr);

        if ($insert_school_res) {
            $school_id = $wpdb->insert_id;

            if (!empty($_FILES['document']['name'][0])) {
                // calling Function to upload school certificates...
                if (uploadSchoolCertificates($school_id, $wpdb, $path)) {

                    if (sendMail($token, $school_id, $rand_password)) {

                        // commiting the transaction...
                        $wpdb->query('COMMIT');

                        $response = ['status' => 200, 'message' => 'School Created Successfully'];

                    }
                }
            } else {
                // commiting the transaction...
                $wpdb->query('COMMIT');
                $response = ['status' => Success_Code, 'message' => 'School created Successfully'];
            }

        } else {
            $response = ['status' => Error_Code, 'message' => 'School not created due to internal server error.'];
        }
    }

    echo json_encode($response);
    exit;
}

function uploadImage($image)
{
    $allowedExtensions = ['jpeg', 'jpg', 'png'];
    $path = dirname(__DIR__, 1);

    //validating profile image size for not greater than 2MB...
    if ($image['size'] > 2 * 1024 * 1024) {
        throw new Exception('profile image size should not be more than 2 MB');
    }

    //validating profile image type of only allowed types...
    if (!in_array(pathinfo($image['name'], PATHINFO_EXTENSION), $allowedExtensions)) {
        throw new Exception('Only jpg,jpeg and png formats are allowed');
    }

    // generating a new image name using time function...
    $image_name = microtime() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);

    // upload profile image to folder...
    if (!move_uploaded_file($image['tmp_name'], $path . '/assets/images/' . $image_name)) {
        throw new Exception('File not uploaded');
    }
    return $image_name;
}

// function to upload school certificates...

function uploadSchoolCertificates($school_id, $wpdb, $path)
{
    $allowedTypes = ['jpeg', 'jpg', 'png', 'pdf', 'docx'];

    foreach ($_FILES['document']['size'] as $key => $size) {
        $name = $_FILES['document']['name'];

        // get the extension of each certificate...
        $ext = pathinfo($name[$key], PATHINFO_EXTENSION);

        // if the type of certificate is not found in allowed type...
        if (!in_array($ext, $allowedTypes)) {
            throw new Exception('Certificates of only jpg,jpeg,png,pdf and docx formats are allowed');
        }

        // if size of any certificate exceeds 2 MB...
        if ($size > 2 * 1024 * 1024) {
            throw new Exception('Document size should not exceed 2 MB');
        }

        // generating the name of certificate file...
        $doc_name = microtime() . '.' . $ext;
        // echo $doc_name;die;

        // inserting school certificates in school certificate table...
        $insert_school_certificates = ['school_id' => $school_id, 'document' => $doc_name, 'created_at' => Date('Y-m-d h:i:s')];

        // uploading certificate to image folder...
        if (!move_uploaded_file($_FILES['document']['tmp_name'][$key], $path . '/assets/certificates/' . $doc_name)) {
            throw new Exception('Certificates not uploaded due to error');
        }
        $result = $wpdb->insert('school_certificate', $insert_school_certificates);
        if (!$result) {
            throw new Exception('Certificates not inserted due to error');
        }
    }
    return true;
}

// function to send mail to school for login...

function sendMail($token, $id, $password)
{

    // encoding School id...
    $id = base64_encode($id);

    // url of the verification page...
    $url = get_home_url() . '/index.php/account-verification/?tok=' . $token . '&school=' . $id . '&type=school';

    // html to render when mail will be sent to user...
    $msg = '<h1>Hello ' . $_POST['name'] . '\n Welcome To Apply board.</h1><p>' . $url . " Please verify your account on clicking the link given below.</p><a class='btn btn-primary' href=" . $url . "></a>
         <h3>Email :</h3>" . $_POST['email'] . '\n <h3>Password : ' . $password . '</h3>';

    try {
        // sending mail to user...
        $mail_res = wp_mail($_POST['email'], '<h3>Activate Your Applyboard Account</h3>', $msg);

        // if mail success...
        if ($mail_res) {
            return true;
        } else {
            throw new Exception('Mail not sent due to Internal server error');
        }
    }
    // return error response...
     catch (Exception $e) {
        $response = ['status' => 400, 'message' => $e->getMessage()];
        echo json_encode($response);
    }

}
