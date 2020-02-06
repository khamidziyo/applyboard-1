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

if (!empty($_GET['val'])) {
    try {
        if (verifyUser()) {
            switch ($_GET['val']) {
                case 'getApplications':
                    $id = $payload->userId;

                    if (empty($_GET['student'])) {
                        throw new Exception("Student id is required");
                    }

                    $student_id = base64_decode($_GET['student']);

                    $agent_id[] = $id;

                    $agents = $wpdb->get_results("select id from agents where created_by=$id");

                    if (!empty($agents)) {
                        foreach ($agents as $key => $obj) {
                            $agent_id[] = $obj->id;
                        }
                    }
                    $start = $_GET['start'];
                    $length = $_GET['length'];

                    // set the limit of query...
                    $limit = 'limit ' . $start . ',' . $length;

                    // sort array...
                    $sort_arr = ['applications.id', 'u.f_name', 'u.l_name', 'agents.name', 's.name',
                        'c.name', 'applications.created_at'];

                    // // order by...
                    $order_by = 'order by ' . $sort_arr[$_GET['order'][0][column]] . ' ' . $_GET['order'][0][dir];

                    // search array to search by column name...
                    $srch_arr = ['applications.id', 'u.f_name', 'u.l_name', 'agents.name', 's.name',
                        'c.name', 'applications.created_at'];

                    // if search value is not empty....
                    if (!empty($_GET['search'][value])) {
                        $where = '&& ';

                        $srch_val = $_GET['search'][value];
                        foreach ($srch_arr as $col_name) {
                            $where .= $col_name . " like '%" . $srch_val . "%' or ";
                        }

                        // repalcing the 'or' from last of the string...
                        $where = substr_replace($where, '', -3);
                    }

                    $sql = "select applications.*,s.name as s_name,c.name as c_name,CONCAT( users.f_name,' ',users.l_name)
                     AS u_name,agents.name as
                     agent_name from agents join applications on applications.agent_id=agents.id join
                     users on users.id=applications.student_id join school as s on
                     s.id=applications.school_id join courses as c on c.id=applications.course_id
                     where applications.agent_id in (" . implode(",", $agent_id) . ") &&
                     applications.student_id=" . $student_id;

                    // query to get the total results...
                    $total_applications = $wpdb->get_results($sql);

                    $sql = $sql . ' ' . $where . ' ' . $order_by . ' ' . $limit;

                    // query to display the filter results....
                    $display_applications = $wpdb->get_results($sql);

                    // if the records is not empty...
                    if (!empty($display_applications)) {
                        foreach ($display_applications as $key => $obj) {
                            $record = [];
                            $record[] = $obj->id;
                            $record[] = $obj->u_name;
                            $record[] = $obj->agent_name;
                            $record[] = $obj->s_name;
                            $record[] = $obj->c_name;
                            $record[] = Date('d-m-Y', strtotime($obj->created_at));

                            if ($obj->status == '0') {
                                $record[] = "Pending";
                            } elseif ($obj->status == '1') {
                                $record[] = "Approved";
                            } elseif ($obj->status == '2') {
                                $record[] = "Decline";
                            }
                            $record[] = "<input type='button' value='Delete Application'
                             class='btn btn-danger delete_application' data_id=" . base64_encode($obj->id) . ">";

                            $output['aaData'][] = $record;
                        }
                        $output['iTotalDisplayRecords'] = count($total_applications);
                        $output['iTotalRecords'] = count($display_applications);
                    } else {
                        $output['aaData'] = [];
                        $output['iTotalDisplayRecords'] = 0;
                        $output['iTotalRecords'] = 0;
                    }

                    // return the json output...
                    echo json_encode($output);
                    exit;

                    break;
                default:
                    throw new Exception("No match Found");
                    break;
            }
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
    echo json_encode($response);
}
