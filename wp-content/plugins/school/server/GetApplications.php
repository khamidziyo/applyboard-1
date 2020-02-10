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

if (!empty($_GET['val'])) {
    try {
        if (verifyUser()) {
            switch ($_GET['val']) {
                case 'getSchoolApplications':
                    $id = $payload->userId;
                    $where = "";

                    $start = $_GET['start'];
                    $length = $_GET['length'];
                    $limit = 'limit ' . $start . ',' . $length;

                    $sort_arr = ['a.id', '', 'c.name', '', 'a.created_at'];

                    $order_by = 'order by ' . $sort_arr[$_GET['order'][0][column]] . ' ' . $_GET['order'][0][dir];

                    $srch_arr = ['a.id', 'u.f_name', 'u.l_name', 'c.name'];

                    if (!empty($_GET['search'][value])) {
                        $where = " && ";

                        $srch_val = $_GET['search'][value];
                        foreach ($srch_arr as $col_name) {
                            $where .= $col_name . " like '%" . $srch_val . "%' or ";
                        }

                        $where = substr_replace($where, '', -3);
                    }
                    
                    $sql = "select a.id,a.status,a.created_at,CONCAT(u.f_name, ' ', u.l_name) AS name,
                    c.name as c_name,u.id as u_id from applications as a join courses as c on
                    c.id=a.course_id join users as u on u.id=a.student_id where a.school_id=" . $id;

                    // all the courses that matches with user interest in user_interest table...
                    $total_applications = $wpdb->get_results($sql . $where);

                    $sql = $sql . ' ' . $where . ' ' . $order_by . ' ' . $limit;
                    // echo $sql;die;

                    $display_applications = $wpdb->get_results($sql);

                    if (!empty($display_applications)) {

                        foreach ($display_applications as $key => $obj) {
                            $record = [];
                            $record[] = $obj->id;
                            $record[] = $obj->name;
                            $record[] = $obj->c_name;

                            switch ($obj->status) {
                                case "0":
                                    $record[] = "<input type='button' class='btn btn-primary' Value='No Action Taken'>";
                                    break;
                                case "1":
                                    $record[] = "<input type='button' class='btn btn-success' Value='Approved'>";
                                    break;
                                case "2":
                                    $record[] = "<input type='button' class='btn btn-danger' Value='Declined'>";
                                    break;
                            }
                            $record[] = date("d-m-Y h:i:s", strtotime($obj->created_at));
                            $record[] = "<input type='button' class='btn btn-primary view' user_id=" . base64_encode($obj->u_id) . " app_id=" . base64_encode($obj->id) . " name='View' Value='View'>";
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

            }

        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => "Unauthorized Access.Value is required"];
}

echo json_encode($response);
