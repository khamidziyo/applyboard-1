<?php

global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function studentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Student::verifyUser($payload);
}

if (!empty($_POST['val'])) {
    try {
        switch ($_POST['val']) {

            case 'removeDocument':
                if (studentVerifyUser()) {

                    if (empty($_POST['doc_id'])) {
                        throw new Exception("Document Id not found in request");
                    }
                    $doc_id = $_POST['doc_id'];

                    $wpdb->query('START TRANSACTION');

                    $doc = $wpdb->get_results("select document from user_documents where id=" . $doc_id);
                    $doc_name = $doc[0]->document;

                    $delete = $wpdb->query("delete from user_documents where id=" . $doc_id);

                    $path = dirname(__DIR__, 1) . '/assets/documents/';
 
                    if ($delete) {
                        if (!unlink($path . $doc_name)) {
                            throw new Exception("Document could not be deleted from directory.Try again later");
                        }
                        $wpdb->query('COMMIT');
                        $response = ['status' => Success_Code, 'message' => 'Document Deleted Successfully'];
                    }
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
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}
echo json_encode($response);
