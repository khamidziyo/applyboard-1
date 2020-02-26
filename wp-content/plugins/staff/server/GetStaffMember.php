<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

function adminVerifyUser()
{
    $payload = JwtToken::getBearerToken();

    return Admin::verifyUser($payload);
}

if (!empty($_GET['val'])) {

    switch ($_GET['val']) {
        case 'getStaffMemberByAdmin':
            if (adminVerifyUser()) {

                $start = $_GET['start'];
                $length = $_GET['length'];
                $limit = 'limit ' . $start . ',' . $length;

                $sort_arr = ['id', 'name', 'email'];

                $order_by = 'order by ' . $sort_arr[$_GET['order'][0][column]] . ' ' . $_GET['order'][0][dir];

                $srch_arr = ['s.id', 's.name', 's.email'];

                if (!empty($_GET['search'][value])) {
                    $where = '&& ';

                    $srch_val = $_GET['search'][value];
                    foreach ($srch_arr as $col_name) {
                        $where .= $col_name . " like '%" . $srch_val . "%' or ";
                    }

                    $where = substr_replace($where, '', -3);
                }

                $sql = "select s.id,s.name,s.email,s.image,s.created_at,s.status from staff as s ";

                $total_records = $wpdb->get_results($sql);

                $sql = $sql . ' ' . $where . ' ' . $order_by . ' ' . $limit;

                $display_records = $wpdb->get_results($sql);

                if (!empty($display_records)) {
                    foreach ($display_records as $key => $obj) {
                        $record = [];
                        $record[] = $obj->id;
                        $record[] = $obj->name;
                        $record[] = $obj->email;
                        $record[] = "<img src='".staff_asset_url.'images/'.$obj->image."' width='50px' height='50px'>";
                        $record[] = Date("Y-m-d", strtotime($obj->created_at));

                        switch ($obj->status) {
                            case '1':
                                $active = 'selected="selected"';
                                break;

                            case '2':
                                $inactive = 'selected="selected"';
                                break;
                        }
                        // echo  $pending;
                        $record[] = "<select class='update_status' staff_id=" . base64_encode($obj->id) . ">
                         <option value='1'" . $active . ">Active</option>
                         <option value='2'" . $inactive . ">Inactive</option>
                         </select>";

                        $record[] = "<input type='button' value='View Profile' s_id=" . base64_encode($obj->id) . " class='btn btn-primary view_profile'>&nbsp;&nbsp;";
                        $output['aaData'][] = $record;
                    }
                    $output['iTotalDisplayRecords'] = count($total_records);
                    $output['iTotalRecords'] = count($display_records);
                } else {
                    $output['aaData'] = [];
                    $output['iTotalDisplayRecords'] = 0;
                    $output['iTotalRecords'] = 0;
                }

                echo json_encode($output);

                // echo "<pre>";
                // print_r($_GET);
                // die;
            }

            break;
    }
}
