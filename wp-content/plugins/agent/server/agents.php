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
    return Admin::verifyUser($payload);
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
                    $sort_arr = ['a.id', 'u.email', 'a.name', 'a.email'];

                    // order by...
                    $order_by = 'order by ' . $sort_arr[$_GET['order'][0][column]] . ' ' . $_GET['order'][0][dir];

                    // search array to search by column name...
                    $srch_arr = ['a.id', 'u.email', 'a.name', 'a.email', 'a.contact_number', 'a.address'];

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

                    $sql = "select a.id,u.email as u_email,a.name,a.email as a_email,a.contact_number,
                    a.address,a.image from agents as a inner join users as u on u.id=a.created_by ";

                    // query to get the total results...
                    $total_agents = $wpdb->get_results($sql);

                    $sql = $sql . ' ' . $where . ' ' . $order_by . ' ' . $limit;

                    // query to display the filter results....
                    $display_agents = $wpdb->get_results($sql);

                    // if the records is not empty...
                    if (!empty($display_agents)) {
                        foreach ($display_agents as $key => $obj) {
                            $record = [];
                            $record[] = $obj->id;
                            $record[] = $obj->u_email;
                            $record[] = $obj->name;
                            $record[] = $obj->a_email;
                            $record[] = $obj->contact_number;
                            $record[] = $obj->address;
                            $record[] = "<img src='".agent_asset_url.'images/'.$obj->image."' width='50px' height='50px'>";
                            $record[] = "<input type='button' value='View' class='btn btn-primary view'>&nbsp;&nbsp;";
                            $output['aaData'][] = $record;
                        }
                        $output['iTotalDisplayRecords'] = count($total_agents);
                        $output['iTotalRecords'] = count($display_agents);
                    } else {
                        $output['aaData'] = [];
                        $output['iTotalDisplayRecords'] = 0;
                        $output['iTotalRecords'] = 0;
                    }

                    // return the json output...
                    echo json_encode($output);
                    exit;

                    echo "<pre>";
                    print_r($_GET);
                    die;
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
