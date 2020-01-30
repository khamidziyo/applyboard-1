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

    try {
        if (verifyUser()) {
            if (empty($_POST['c_id'])) {
                throw new Exception("Course id is required");
            }

            // decoding the course id...
            $course_id = base64_decode($_POST['c_id']);

            // get the course image before deltion...
            $image = $wpdb->get_results("select image from courses where id=" . $course_id);

            // query to delete the course...
            $sql = "DELETE  FROM courses WHERE id =" . $course_id;

            // delete the image file from folder...
            if (!unlink(dirname(__DIR__) . "/assets/images/" . $image[0]->image)) {
                throw new Exception("Unable to delete image from server.Try again");
            }

            $del = $wpdb->query($sql);

            // if success...
            if ($del) {
                $response = ['status' => Success_Code, 'message' => 'Course deleted successfully'];
            } else {
                throw new Exception("Course not deleted due to internal server error.Try again");
            }
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }

}

// if user directly access this page...
else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access'];
}

// returning the json response...
echo json_encode($response);
