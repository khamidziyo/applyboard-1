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
                case 'getAgents':

                    $id = $payload->userId;

                    $start = $_GET['start'];
                    $length = $_GET['length'];

                    // set the limit of query...
                    $limit = 'limit ' . $start . ',' . $length;

                    // sort array...
                    $sort_arr = ['a.id', 'a.email', 'a.created_at'];

                    // order by...
                    $order_by = 'order by ' . $sort_arr[$_GET['order'][0][column]] . ' ' . $_GET['order'][0][dir];

                    // search array to search by column name...
                    $srch_arr = ['a.id', 'a.email', 'a.created_at'];

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

                    $sql = "select a.id,a.email,a.status,a.created_at from agents as a where created_by=" . $id;

                    // query to get the total results...
                    $total_sub_agents = $wpdb->get_results($sql);

                    $sql = $sql . ' ' . $where . ' ' . $order_by . ' ' . $limit;

                    // query to display the filter results....
                    $display_sub_agents = $wpdb->get_results($sql);

                    // if the records is not empty...
                    if (!empty($display_sub_agents)) {
                        foreach ($display_sub_agents as $key => $obj) {
                            $record = [];
                            $record[] = $obj->id;
                            $record[] = $obj->email;

                            if ($obj->status == 1) {
                                $active = 'selected="selected"';
                                $record[] = "<a class='btn btn-success'>Account Active</a>";
                            } elseif ($obj->status == 2) {
                                $inactive = 'selected="selected"';
                                $record[] = "<a class='btn btn-danger'>Account Deactivated</a>";
                            }
                            $record[] = Date('d-m-Y h:i:s', strtotime($obj->created_at));

                            ?>
                           <?php $record[] = "<select class='sub_agent_status' data_id=$obj->id>
                            <option value='2'" . $inactive . ">Inactive</option>
                            <option value='1'" . $active . ">Active</option>
                            </select>&nbsp;&nbsp;<a class='btn btn-primary change_password' data_id=" . $obj->id . ">Change Password</a>";
                            $output['aaData'][] = $record;
                        }
                        $output['iTotalDisplayRecords'] = count($total_sub_agents);
                        $output['iTotalRecords'] = count($display_sub_agents);

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
} else {
    $response = ['status' => Error_Code, 'message' => 'Unauthorized Access.Value is required'];
}
echo json_encode($response);
