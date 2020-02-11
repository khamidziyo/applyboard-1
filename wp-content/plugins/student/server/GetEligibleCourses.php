<?php
global $wpdb;

if (!isset($wpdb)) {
    include_once '../../../../wp-config.php';
}

if (file_exists(dirname(__FILE__, 3) . '/common/autoload.php')) {
    include_once dirname(__FILE__, 3) . '/common/autoload.php';
}

// function to verify user...

function studentVerifyUser()
{
    global $payload;

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Student::verifyUser($payload);
}

function agentVerifyUser()
{
    global $payload;

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return Agent::verifyUser($payload);
}

function subAgentVerifyUser()
{
    global $payload;

    // jwt token class defined in jwttoken.php file inside common directory of plugin...
    $payload = JwtToken::getBearerToken();

    // Student class defined in student.php file inside common directory of plugin...
    return SubAgent::verifyUser($payload);
}

if (!empty($_GET)) {
    try {
        switch ($_GET['val']) {

            // case to view eligible courses...
            case 'getEligibleCourses':
                if (studentVerifyUser()) {
                }

            case 'getEligibleCoursesByAgent':
                if (agentVerifyUser()) {

                }
                break;

            case 'getEligibleCoursesBySubAgent':
                if (subAgentVerifyUser()) {

                }
                break;

            // if no case matches...
            default:
                throw new Exception('No match found');
                break;

        }
        $cmplt_eligible_arr = [];
        $partial_eligible_arr = [];
        $countries = [];
        $categories = [];
        $disciplines = [];
        $schools = [];

        $start = $_GET['start'];
        $length = $_GET['length'];
        $limit = 'limit ' . $start . ',' . $length;

        $sort_arr = ['id', 'name'];

        if (!empty($_GET['filter'])) {
            $filter_sql = "where ";

            $filter_arr = $_GET['filter'];
            foreach ($filter_arr as $key => $arr) {
                switch ($arr['name']) {

                    case 'country':
                        // $filter_sql.="s.countries_id in ".$arr['value'].",";

                        $countries[] = $arr['value'];
                        break;

                    case 'category':
                        $categories[] = $arr['value'];
                        break;

                    case 'disciplines':
                        $disciplines[] = $arr['value'];
                        break;

                    case 'school':
                        $schools[] = $arr['value'];
                        break;
                }
            }

            if (!empty($countries)) {
                $filter_sql .= " s.countries_id in (" . implode(",", $countries) . ")   &&";
            }

            if (!empty($categories)) {
                $filter_sql .= " category_id in (" . implode(",", $categories) . ")  &&";
            }

            if (!empty($disciplines)) {
                $filter_sql .= " type_id in (" . implode(",", $disciplines) . ")   &&";
            }

            if (!empty($schools)) {
                $filter_sql .= " school_id in  (" . implode(",", $schools) . ")   &&";
            }
        }

        $filter_sql = substr_replace($filter_sql, "", -3);

        $order_by = 'order by ' . $sort_arr[$_GET['order'][0][column]] . ' ' . $_GET['order'][0][dir];

        $srch_arr = ['c.id', 'c.name', 'c.code', 'type.name', 'category.name'];

        $id = $payload->userId;

        $student_id = base64_decode($_GET['student']);
        // echo $student_id;
        // die;
        if (!empty($_GET['search'][value])) {

            $srch_val = $_GET['search'][value];
            foreach ($srch_arr as $col_name) {
                $where .= $col_name . " like '%" . $srch_val . "%' or ";
            }

            $where = substr_replace($where, '', -3);
        }

        $sql = "select c.id,c.name,c.code,c.exam_marks,s.name as s_name,type.name as type_name,
        category.name as category_name from courses as c join type on type.id=c.type_id join category
        on category.id=c.category_id join school as s on s.id=c.school_id  " . $filter_sql . "order by id desc";

        $total_courses = $wpdb->get_results($sql);

        $user = $wpdb->get_results("select exam from users where id=$student_id");

        $user_exam = json_decode($user[0]->exam, true);

        foreach ($total_courses as $key => $course_obj) {
            $count = 0;

            $course_exam = json_decode($course_obj->exam_marks, true);

            if (is_array($course_exam)) {

                foreach ($course_exam as $exam_id => $sub_arr) {
                    if (array_key_exists($exam_id, $user_exam)) {

                        foreach ($sub_arr as $subject => $course_sub_marks) {
                            // echo $subject." ".$user_exam[$exam_id][$subject];
                            // echo $subject." ".$course_sub_marks."<br>";
                            if ($user_exam[$exam_id][$subject] >= $course_sub_marks) {
                                $count++;
                            }
                        }
                        if ($count == 4) {
                            $complete_eligible_arr[] = json_decode(json_encode($course_obj), true);
                            break;
                        }
                    }
                }
            }
            if ($count != 4) {
                $partial_eligible_arr[] = json_decode(json_encode($course_obj), true);
            }
        }

        // print_r($partial_eligible_arr);
        // die;

        $sql = $sql . ' ' . $where . ' ' . $order_by . ' ' . $limit;

        if (!empty($complete_eligible_arr)) {

            foreach ($complete_eligible_arr as $key => $arr) {

                $record = [];
                $record[] = $arr['id'];
                $record[] = $arr['name'];
                $record[] = $arr['s_name'];
                $record[] = $arr['code'];
                $record[] = $arr['type_name'];
                $record[] = $arr['category_name'];
                $applications = $wpdb->get_results("select id from applications where course_id=" . $arr['id'] . " && student_id=" . $student_id);

                if (!empty($applications[0])) {
                    $record[] = "<input type='button' class='btn btn-success' value='Already Applied' disabled>";
                } else {
                    $record[] = "<input type='button' class='btn btn-success apply' value='Apply' c_id=" . base64_encode($arr['id']) . '>';
                }
                $output['aaData'][] = $record;
            }
        }

        if (!empty($partial_eligible_arr)) {

            foreach ($partial_eligible_arr as $key => $arr) {
                $record = [];
                $record[] = $arr['id'];
                $record[] = $arr['name'];
                $record[] = $arr['s_name'];
                $record[] = $arr['code'];
                $record[] = $arr['type_name'];
                $record[] = $arr['category_name'];
                $record[] = "<input type='button' name='not_eligible' value='Not Eligible' class='not_eligible_btn btn btn-danger' c_id=" . base64_encode($arr['id']) . ">";

                $output['aaData'][] = $record;
            }
        }
        if (empty($complete_eligible_arr) && empty($partial_eligible_arr)) {
            $output['aaData'] = [];
        }

        $output['iTotalDisplayRecords'] = count($total_courses);
        $output['iTotalRecords'] = count($display_courses);

        echo json_encode($output);
        exit;
    } catch (Exception $e) {
        $response = ['status' => Error_Code, 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => Error_Code, 'message' => 'unauthorized Access'];
}
echo json_encode($response);
