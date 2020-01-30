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

                case 'getCourses':

                    $start = $_GET['start'];
                    $length = $_GET['length'];
                    $limit = "limit " . $start . "," . $length;

                    $sort_arr = ['id'];

                    $order_by = "order by " . $sort_arr[$_GET['order'][0][column]] . " " . $_GET['order'][0][dir];

                    $srch_arr = ['c.id', 'c.name', 'c.code', 'c.start_date', 'c.end_date', 'c.created_at', 'type.name', 'category.name'];

                    if (!empty($_GET['search'][value])) {
                        $where = "&& ";

                        $srch_val = $_GET['search'][value];
                        foreach ($srch_arr as $col_name) {
                            $where .= $col_name . " like '%" . $srch_val . "%' or ";
                        }

                        $where = substr_replace($where, '', -3);

                        // echo $where;die;
                    }

                    $school_id = $payload->userId;

                    $sql = "select c.id,c.name,c.code,c.start_date,c.end_date,c.created_at,type.name as
                     t_name,category.name as cat_name  from courses as c join type on type.id=c.type_id
                      join category  on c.category_id=category.id where c.school_id=" . $school_id;

                    $total_records = $wpdb->get_results($sql.$where);

                    $sql = $sql . " " . $where . " " . $order_by . " " . $limit;
           
                    $display_records = $wpdb->get_results($sql);

                    if (!empty($display_records)) {
                        foreach ($display_records as $key => $obj) {
                            $record = [];
                            $record[] = $obj->id;
                            $record[] = $obj->name;
                            $record[] = $obj->code;
                            $record[] = $obj->t_name;
                            $record[] = $obj->cat_name;
                            $record[] = date("d-m-Y", strtotime($obj->start_date));
                            $record[] = date("d-m-Y", strtotime($obj->end_date));
                            $record[] = date("d-m-Y", strtotime($obj->created_at));

                            $record[] = "<input type='button' value='View' c_id=" . base64_encode($obj->id) . " class='btn btn-primary view'>&nbsp;&nbsp;
                    <input type='button' value='Edit' c_id=" . base64_encode($obj->id) . " class='btn btn-primary edit'>&nbsp;&nbsp;
                    <input type='button' value='Delete' c_id=" . base64_encode($obj->id) . " class='btn btn-danger delete'>";
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

                    break;

            }
        }
    } catch (Exception $e) {
        $response = ['status' => 400, 'message' => $e->getMessage()];
        echo json_encode($response);

    }
}
