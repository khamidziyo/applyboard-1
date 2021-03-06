<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function agentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return Agent::verifyUser($payload);
}

function subAgentVerifyUser()
{
    global $payload;
    $payload = JwtToken::getBearerToken();
    return SubAgent::verifyUser($payload);
}

if (!empty($_GET['val'])) {
    try {
        switch ($_GET['val']) {
            case 'getStudentsByAgent':
                if (agentVerifyUser()) {
                    $id = $payload->userId;
                    $agents = $wpdb->get_results("select id from agents where created_by=" . $id);

                    $agent_id[] = $id;
                    foreach ($agents as $key => $obj) {
                        $agent_id[] = $obj->id;
                    }
                    $agent_ids = implode(",", $agent_id);

                    $sql = "select u.*,c.name as cntry_name,(SELECT COUNT(*) FROM applications as a
                 WHERE a.student_id=u.id) as total_application from users as u join countries as c
                 on c.id=u.nationality where u.agent_id in (" . $agent_ids . ")";

                    getStudentByAgents($wpdb, $sql);
                }
                break;

            case 'getStudentsBySubAgent':
                if (subAgentVerifyUser()) {
                    $id = $payload->userId;

                    $user = $wpdb->get_results("select created_by,permission from agents where id=" . $id . " && role='4'");
                    $access_level = $user[0]->permission;

                    if ($access_level) {
                        $agent_id = $user[0]->created_by;
                        $ids = $id . "," . $agent_id;
                        $where = "where u.agent_id in (" . $ids . ")";
                    } else {
                        $where = "where u.agent_id=" . $id;
                    }
                    $sql = "select u.*,c.name as cntry_name,(SELECT COUNT(*) FROM applications as a
                     WHERE a.student_id=u.id) as total_application from users as u join countries as c
                     on c.id=u.nationality " . $where;

                    getStudentByAgents($wpdb, $sql);
                }
                break;

            default:
                throw new Exception("No match Found");
                break;
        }
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
    // echo json_encode($response);
}

function getStudentByAgents($wpdb, $sql)
{

    $start = $_GET['start'];
    $length = $_GET['length'];

    // set the limit of query...
    $limit = 'limit ' . $start . ',' . $length;

    // sort array...
    $sort_arr = ['u.id', 'u.f_name', 'u.l_name', 'u.email', 'a.dob', 'u.nationality', 'u.gender'];

    // order by...
    $order_by = 'order by ' . $sort_arr[$_GET['order'][0][column]] . ' ' . $_GET['order'][0][dir];

    // search array to search by column name...
    $srch_arr = ['u.id', 'u.f_name', 'u.l_name', 'u.email', 'u.dob', 'u.nationality', 'u.gender'];

    // if search value is not empty....
    if (!empty($_GET['search'][value])) {
        $where = '&& ';

        $srch_val = $_GET['search'][value];
        foreach ($srch_arr as $col_name) {
            $where .= $col_name . " like '%" . $srch_val . "%' or ";
        }
    }

    // repalcing the 'or' from last of the string...
    $where = substr_replace($where, '', -3);

    // query to get the total results...
    $total_students = $wpdb->get_results($sql . $where);

    $sql = $sql . ' ' . $where . ' ' . $order_by . ' ' . $limit;

    // query to display the filter results....
    $display_students = $wpdb->get_results($sql);

    // if the records is not empty...
    if (!empty($display_students)) {
        foreach ($display_students as $key => $obj) {
            $record = [];
            $record[] = $obj->id;
            $record[] = $obj->f_name;
            $record[] = $obj->l_name;
            $record[] = $obj->email;
            $record[] = Date('d-m-Y', strtotime($obj->dob));
            $record[] = $obj->cntry_name;

            if ($obj->gender == '1') {
                $record[] = "Male";
            } elseif ($obj->gender == '2') {
                $record[] = "Female";
            }
            $record[] = $obj->total_application . "<br> application submitted";
            $record[] = "<img src='" . student_asset_url . '/images/' . $obj->image . "' width='50px' height='50px'>";
            $record[] = "<input type='button' value='View Applications'
                                     class='btn btn-primary view_application' data_id=" . base64_encode($obj->id) . ">
                                     <input type='button' value='Create Application' data_id=" . base64_encode($obj->id) . "
                                     class='btn btn-success create_application'><input type='button' value='Edit User'
                                     data_id=" . base64_encode($obj->id) . " class='btn btn-primary edit_user'>";
            $record[] = "<button type='button' class='chatUser btn btn-primary' data_id=$obj->id>Chat</button>";

            $output['aaData'][] = $record;
        }
        $output['iTotalDisplayRecords'] = count($total_students);
        $output['iTotalRecords'] = count($display_students);
    } else {
        $output['aaData'] = [];
        $output['iTotalDisplayRecords'] = 0;
        $output['iTotalRecords'] = 0;
    }

    // return the json output...
    echo json_encode($output);
    exit;
}
