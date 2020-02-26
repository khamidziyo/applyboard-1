<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once dirname(__FILE__, 4) . "/wp-config.php";
}

class Staff
{

    // admin class that checks for the user...
    public static function verifyUser($payload)
    {

        global $wpdb;

        try {

            // get the user id from payload...
            $user_id = $payload->userId;

            // query to match the user role w.rt user id admin ...
            $sql = "select email,status from staff where id=" . $user_id . " && role='5'";

            // query to get the data with user id and role...
            $user = $wpdb->get_results($sql);

            // echo "<pre>";
            // print_r($user);
            // die;
            // if user is authenticated...
            if (!empty($user)) {

                switch ($user[0]->status) {

                    case '1':
                        return true;

                        break;
                    case '2':
                        throw new Exception("Your account is deactivated.Please contact to admin");
                        break;

                    default:
                        throw new Exception("No case matches.Unauthorized Access");
                        break;

                }

                // if useris not authenticated...
            } else {
                throw new Exception("Unauthorized Access to staff login");
            }

            // catching the exception...
        } catch (Exception $e) {
            $response = ['status' => 117, 'message' => $e->getMessage()];
            echo json_encode($response);
            exit;
        }

    }
}
