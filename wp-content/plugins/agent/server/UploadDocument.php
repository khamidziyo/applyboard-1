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
                case 'uploadDocument':

                    $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];

                    if (empty($_POST['student_id'])) {
                        throw new Exception("Student id is required");
                    }

                    if (empty($_FILES['upload_document']['name'][0])) {
                        throw new Exception("Please upload the documents");
                    }

                    $student_id = $_POST['student_id'];

                    foreach ($_FILES['upload_document']['name'] as $key => $doc_name) {

                        $doc_type = pathinfo($doc_name, PATHINFO_EXTENSION);

                        if (!in_array($doc_type, $allowedTypes)) {
                            throw new Exception("Only jpg,jpeg and png formats are allowed");
                        }

                        if ($_FILES['upload_document']['size'][$key] > 2 * 1024 * 1024) {
                            throw new Exception("Document size Should not exceed more than 2 MB");
                        }

                        $doc_name = microtime() . "." . $doc_type;

                        $path = dirname(__DIR__, 2) . '/student/assets/documents/';
 
                        if (!move_uploaded_file($_FILES['upload_document']['tmp_name'][$key], $path . $doc_name)) {
                            throw new Exception("Document could not upload on server due to internal server error");
                        }

                        $insert_arr = ['user_id' => $student_id, 'document' => $doc_name, 'created_at' => Date('Y-m-d h:i:s')];

                        $insert_res = $wpdb->insert('user_documents', $insert_arr);
                    }

                    if ($insert_res) {
                        $response = ['status' => Success_Code, 'message' => 'Document Uploaded Successfully'];
                    } else {
                        throw new Exception("Document not uploaded due to internal server error");
                    }

                    break;

                default:
                    throw new Exception("No case found");
                    break;
            }
        } catch (Exception $e) {
            $response = ['status' => Error_Code, 'message' => $e->getMessage()];
        }
    }

} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}

// returning the json response...
echo json_encode($response);
