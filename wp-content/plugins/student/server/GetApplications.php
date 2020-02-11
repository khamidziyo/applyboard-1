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
    global $payload;

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser($payload);
}

if (!empty($_GET['val'])) {

    try {
        if (verifyUser()) {
            switch ($_GET['val']) {
                case 'getStudentApplications':
                    $id = $payload->userId;
                    $where = "";

                    $start = $_GET['start'];
                    $length = $_GET['length'];
                    $limit = 'limit ' . $start . ',' . $length;

                    $sort_arr = ['a.id', 's.name', 'c.name', '', 'a.created_at'];

                    $order_by = 'order by ' . $sort_arr[$_GET['order'][0][column]] . ' ' . $_GET['order'][0][dir];

                    $srch_arr = ['a.id', 's.name', 'c.name'];

                    if (!empty($_GET['search'][value])) {
                        $where = " && ";

                        $srch_val = $_GET['search'][value];
                        foreach ($srch_arr as $col_name) {
                            $where .= $col_name . " like '%" . $srch_val . "%' or ";
                        }

                        $where = substr_replace($where, '', -3);
                    }

                    $sql = "select a.id,a.status,a.created_at,c.name as c_name,s.name as s_name from
                     applications as a join courses as c on c.id=a.course_id join
                      school as s on s.id=a.school_id where a.student_id=" . $id;

                    // all the courses that matches with user interest in user_interest table...
                    $total_applications = $wpdb->get_results($sql . $where);

                    $sql = $sql . ' ' . $where . ' ' . $order_by . ' ' . $limit;
                    // echo $sql;die;

                    $display_applications = $wpdb->get_results($sql);

                    if (!empty($display_applications)) {

                        foreach ($display_applications as $key => $obj) {
                            $record = [];
                            $record[] = $obj->id;
                            $record[] = $obj->s_name;
                            $record[] = $obj->c_name;
                            switch ($obj->status) {
                                case '0':
                                    $record[] = "<input type='button' class='btn btn-primary' value='No Action Taken'>";
                                    break;

                                case '1':
                                    $record[] = "<input type='button' class='btn btn-success' value='Accept'>";
                                    break;

                                case '2':
                                    $record[] = "<input type='button' class='btn btn-danger' value='Declined'>";
                                    break;
                            }
                            $record[] = date('d-m-Y h:i:s', strtotime($obj->created_at));
                            $record[] = "<input type='button' class='btn btn-danger delete' value='Delete' a_id=" . base64_encode($obj->id) . '>';
                            $output['aaData'][] = $record;
                        }
                    } else {
                        $output['aaData'] = [];
                    }

                    $output['iTotalDisplayRecords'] = count($total_applications);
                    $output['iTotalRecords'] = count($display_applications);
                    echo json_encode($output);
                    exit;

                    break;

                // if no match found...
                default:
                    throw new Exception("No Match Found");
                    break;
            }
        }

    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is Required."];
}

echo json_encode($response);
