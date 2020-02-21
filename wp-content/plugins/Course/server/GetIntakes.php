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
    if (verifyUser()) {
        try {
            switch ($_GET['val']) {

                case 'getIntakes':

                    if (empty($_GET['course_id'])) {
                        throw new Exception("Course id is required");
                    }

                    $course_id = base64_decode($_GET['course_id']);

                    $intake_ids = $wpdb->get_results("select intake_id from course_intake where course_id=" . $course_id);

                    $intake_arr = [];

                    // get all the previous months that are been added previously so as to not show
                    // in the select dropdown...
                    $where_intake = "";

                    if (!empty($intake_ids)) {
                        foreach ($intake_ids as $key => $obj) {
                            $intake_arr[] = $obj->intake_id;
                        }
                        $where_intake = " && id NOT IN (" . implode(",", $intake_arr) . ")";
                    }

                    // get the current month...
                    $month = Date('m');

                    // get all the inatkes where not matches that exam...
                    $c_intake_next = $wpdb->get_results("select * from intakes where status='1' && id>" . $month . $where_intake);
                    $nxt_intakes = [];
                    $previous_intakes = [];

                    if (!empty($c_intake_next)) {
                        foreach ($c_intake_next as $key => $obj) {
                            $obj->year = Date('Y');
                            $nxt_intakes[] = $obj;
                        }
                    }

                    $c_intake_previous = $wpdb->get_results("select * from intakes where status='1' && id<=" . $month . $where_intake);

                    if (!empty($c_intake_previous)) {
                        foreach ($c_intake_previous as $key => $obj) {
                            $obj->year = Date('Y') + 1;
                            $previous_intakes[] = $obj;
                        }
                    }

                    $c_intakes = array_merge($nxt_intakes, $previous_intakes);

                    $response = ['status' => Success_Code, 'message' => 'Course Intake Fetched Successfully', 'c_intake' => $c_intakes];

                    break;

                case 'getCurrentIntakes':

                    if (empty($_GET['course_id'])) {
                        throw new Exception("Course id is required");
                    }

                    $start = $_GET['start'];
                    $length = $_GET['length'];
                    $limit = "limit " . $start . "," . $length;
                    // echo $start;
                    // die;
                    $sort_arr = ['c_intake.id', 'intakes.name', 'c_intake.start_date', 'c_intake.end_date', 'c_intake.deadline','c_intake.created_at'];

                    $order_by = "order by " . $sort_arr[$_GET['order'][0][column]] . " " . $_GET['order'][0][dir];

                    $srch_arr = ['c_intake.id', 'intakes.name', 'c_intake.start_date', 'c_intake.end_date', 'c_intake.deadline','c_intake.created_at'];

                    if (!empty($_GET['search'][value])) {
                        $where = "&& ";

                        $srch_val = $_GET['search'][value];
                        foreach ($srch_arr as $col_name) {
                            $where .= $col_name . " like '%" . $srch_val . "%' or ";
                        }
                    }
                    $where = substr_replace($where, '', -3);

                    $course_id = base64_decode($_GET['course_id']);

                    $sql = "select *,c_intake.id as course_intake_id,c_intake.created_at as intake_created
                     from course_intake as c_intake join intakes on intakes.id=c_intake.intake_id where course_id=" . $course_id;

                    $total_records = $wpdb->get_results($sql . $where);

                    $sql = $sql . " " . $where . " " . $order_by . " " . $limit;

                    $display_records = $wpdb->get_results($sql);

                    if (!empty($display_records)) {
                        foreach ($display_records as $key => $obj) {
                            $record = [];
                            $record[] = $obj->course_intake_id;
                            $record[] = $obj->name;
                            $record[] = Date("d-m-Y", strtotime($obj->start_date));
                            $record[] = Date("d-m-Y", strtotime($obj->end_date));
                            $record[] = Date("d-m-Y", strtotime($obj->deadline));
                            $record[] = Date("d-m-Y h:i:s", strtotime($obj->intake_created));
                            $record[] = "<button type='button' class='btn btn-danger remove_intake' intake_id=" . base64_encode($obj->course_intake_id) . ">Remove Intake</button>
                            <button type='button' class='btn btn-primary edit_intake' intake_id=" . base64_encode($obj->course_intake_id) . ">Edit Intake</button>";

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
                    exit;
                    break;

                case 'getCourseIntakeDetail':
                    if (empty($_GET['intake_id'])) {
                        throw new Exception("Intake id is required");
                    }

                    $course_intake_id = base64_decode($_GET['intake_id']);

                    // to get course intake detail when school clicks on edit intake button...
                    $course_intakes = $wpdb->get_results("select *,intakes.name as month_name from
                     course_intake join intakes on intakes.id=course_intake.intake_id where course_intake.id=" . $course_intake_id);

                     $intake_detail['start_date']=Date("Y-m-d",strtotime($course_intakes[0]->start_date));
                     $intake_detail['end_date']=Date("Y-m-d",strtotime($course_intakes[0]->end_date));
                     $intake_detail['deadline']=Date("Y-m-d",strtotime($course_intakes[0]->deadline));
                     $intake_detail['month_name']=$course_intakes[0]->month_name;
                     $intake_detail['intake_id']=$course_intakes[0]->intake_id;

                    $response = ['status' => Success_Code, 'course_intake' => $intake_detail];

                    break;
            }
        } catch (Exception $e) {
            $response = ['status' => Error_Code, 'message' => $e->getMessage()];
        }
    }
}
echo json_encode($response);
