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

            switch ($_POST['val']) {
                case 'deleteCertificate':

                    // if application id is empty...
                    if (empty($_POST['id'])) {
                        throw new Exception("Application id is required");
                    }

                    $id = $_POST['id'];

                    // get the document to be deleted...
                    $certificate = $wpdb->get_results("select document from school_certificate where id=" . $id);
                    $doc = $certificate[0]->document;

                    // get the directory path from where image is to be deleted...
                    $path = dirname(__DIR__);

                    // delete the image from the directory...
                    if (!unlink($path . "/assets/certificates/" . $doc)) {
                        throw new Exception('Unable to delete school certificate from server.');
                    }

                    // delete the certificate from database...
                    $del_certificate = $wpdb->query("delete from school_certificate where id=" . $id);

                    // if certificate deleted successfully...
                    if ($del_certificate) {
                        $response = ['status' => Success_Code, 'message' => "Certificate Deleteed successfully"];
                    }
                    // if certificate not deleted from database...
                    else {
                        throw new Exception("Internal server error while deleting school certificate");
                    }
                    break;

                // if no case matches...
                default:
                    throw new Exception("No match Found");
                    break;
            }
        }
    }
    // if any exception occurs...
     catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
}

// if user directly access this page...
else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access"];
}

// returning the json response...
echo json_encode($response);
